<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProbeIntegrationConnectionRequest;
use App\Models\PartnerConnection;
use App\Support\HamaApiClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class IntegrationProbeController extends Controller
{
    public function __invoke(
        ProbeIntegrationConnectionRequest $request,
        HamaApiClient $hamaClient,
    ): RedirectResponse {
        $validated = $request->validated();
        $baseUrl = rtrim($validated['partner_url'], '/');
        $partnerToken = $this->plainToken($validated['partner_token']);
        $shouldSaveConnection = (bool) ($validated['save_connection'] ?? false);

        try {
            [$response, $contract, $endpoint] = $this->probe($hamaClient, $baseUrl, $partnerToken);
        } catch (ConnectionException) {
            return $this->failed($baseUrl, 'No fue posible contactar al otro proyecto. Confirma que esté encendido y que la URL sea correcta.', $shouldSaveConnection);
        }

        if ($response->unauthorized() || $response->forbidden()) {
            return $this->failed($baseUrl, 'El otro proyecto respondió, pero rechazó el token o sus permisos.', $shouldSaveConnection);
        }

        if (! $response->successful()) {
            if ($contract === 'meshiverse' && $response->notFound()) {
                return $this->failed(
                    $baseUrl,
                    'El proyecto respondió, pero no tiene una ruta de conexión compatible. Se probaron POST /api/v1/connections/handshake y GET /api/v1/partner/connection.',
                    $shouldSaveConnection,
                );
            }

            return $this->failed($baseUrl, "El otro proyecto respondió con el código HTTP {$response->status()}.", $shouldSaveConnection);
        }

        $payload = $response->json();
        $connectionResult = is_array($payload)
            ? $this->connectionResult($contract, $payload, $baseUrl)
            : null;

        if ($connectionResult === null) {
            return $this->failed($baseUrl, "La ruta {$endpoint} respondió, pero no confirmó una conexión autenticada.", $shouldSaveConnection);
        }

        if ($contract === 'hama' && $shouldSaveConnection) {
            if (! $this->canReadHamas($payload)) {
                return $this->failed(
                    $baseUrl,
                    'El token fue aceptado, pero no permite consultar Hama. Genera otro con el permiso hamas.read.',
                    true,
                );
            }

            $this->saveHamaConnection($baseUrl, $partnerToken, $payload);

            return to_route('admin.api.hamas.index')
                ->with('success', 'Conexión Hama guardada de forma cifrada. MeshiVerse comprobará ahora la transferencia real de datos.');
        }

        return to_route('admin.api.docs')->with('connection_result', $connectionResult);
    }

    /** @return array{Response, string, string} */
    private function probe(HamaApiClient $hamaClient, string $baseUrl, string $partnerToken): array
    {
        $hamaEndpoint = $baseUrl.'/api/v1/connections/handshake';
        $response = $hamaClient->handshake($baseUrl, $partnerToken);

        if (! $response->notFound()) {
            return [$response, 'hama', $hamaEndpoint];
        }

        $meshiVerseEndpoint = $baseUrl.'/api/v1/partner/connection';
        $response = Http::acceptJson()
            ->withToken($partnerToken)
            ->connectTimeout(3)
            ->timeout(8)
            ->get($meshiVerseEndpoint);

        return [$response, 'meshiverse', $meshiVerseEndpoint];
    }

    /** @param array<string, mixed> $payload */
    private function canReadHamas(array $payload): bool
    {
        return collect(data_get($payload, 'capabilities', []))
            ->contains(function (mixed $capability): bool {
                if (! is_array($capability) || ($capability['allowed'] ?? false) !== true) {
                    return false;
                }

                return ($capability['key'] ?? null) === 'hamas.list'
                    || ($capability['scope'] ?? null) === 'hamas.read';
            });
    }

    /** @param array<string, mixed> $payload */
    private function saveHamaConnection(string $baseUrl, string $partnerToken, array $payload): void
    {
        $capabilities = collect(data_get($payload, 'capabilities', []))
            ->filter(fn (mixed $capability): bool => is_array($capability))
            ->values()
            ->all();
        $scopes = collect(data_get($payload, 'token.scopes', data_get($payload, 'connection.scopes', [])))
            ->filter(fn (mixed $scope): bool => is_string($scope) && $scope !== '')
            ->values()
            ->all();

        PartnerConnection::query()->updateOrCreate(
            ['provider' => PartnerConnection::PROVIDER_HAMA],
            [
                'service_name' => data_get($payload, 'connection.provider', 'ProyectoAPI Hama'),
                'base_url' => $baseUrl,
                'access_token' => $partnerToken,
                'scopes' => $scopes,
                'capabilities' => $capabilities,
                'last_status' => 'connected',
                'records_count' => (int) data_get($payload, 'shared_data.saved_hamas.records', 0),
                'last_verified_at' => now(),
                'last_error' => null,
            ],
        );
    }

    /** @param array<string, mixed> $payload */
    private function connectionResult(string $contract, array $payload, string $baseUrl): ?array
    {
        if ($contract === 'hama') {
            if (data_get($payload, 'success') !== true || data_get($payload, 'connection.status') !== 'connected') {
                return null;
            }

            $sharing = collect(data_get($payload, 'capabilities', []))
                ->filter(fn (mixed $capability): bool => is_array($capability) && ($capability['allowed'] ?? false) === true)
                ->pluck('label')
                ->filter(fn (mixed $label): bool => is_string($label) && $label !== '')
                ->values()
                ->all();

            return [
                'status' => 'connected',
                'service' => data_get($payload, 'connection.provider', 'Proyecto Hama'),
                'message' => data_get($payload, 'message', 'El token OAuth 2.0 del proyecto Hama fue aceptado.'),
                'base_url' => $baseUrl,
                'dish_title' => null,
                'sharing' => $sharing !== [] ? $sharing : ['Token OAuth 2.0 validado'],
            ];
        }

        if (data_get($payload, 'status') !== 'connected') {
            return null;
        }

        return [
            'status' => 'connected',
            'service' => data_get($payload, 'service', 'Proyecto conectado'),
            'message' => data_get($payload, 'message', 'La autenticación entre proyectos fue exitosa.'),
            'base_url' => $baseUrl,
            'dish_title' => data_get($payload, 'preview.dish_of_the_day.title'),
            'sharing' => array_values(array_filter([
                data_get($payload, 'sharing.dish_of_the_day.label'),
                data_get($payload, 'sharing.recipe_suggestions.label'),
            ])),
        ];
    }

    private function plainToken(string $partnerToken): string
    {
        return preg_replace('/^Bearer\s+/i', '', trim($partnerToken)) ?? trim($partnerToken);
    }

    private function failed(string $baseUrl, string $message, bool $returnToHamas = false): RedirectResponse
    {
        return to_route($returnToHamas ? 'admin.api.hamas.index' : 'admin.api.docs')
            ->withErrors(['partner_connection' => $message])
            ->withInput(['partner_url' => $baseUrl]);
    }
}
