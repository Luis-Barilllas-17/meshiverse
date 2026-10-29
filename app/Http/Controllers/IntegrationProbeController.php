<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProbeIntegrationConnectionRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class IntegrationProbeController extends Controller
{
    public function __invoke(ProbeIntegrationConnectionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $baseUrl = rtrim($validated['partner_url'], '/');
        $partnerToken = $this->plainToken($validated['partner_token']);

        try {
            [$response, $contract, $endpoint] = $this->probe($baseUrl, $partnerToken);
        } catch (ConnectionException) {
            return $this->failed($baseUrl, 'No fue posible contactar al otro proyecto. Confirma que esté encendido y que la URL sea correcta.');
        }

        if ($response->unauthorized() || $response->forbidden()) {
            return $this->failed($baseUrl, 'El otro proyecto respondió, pero rechazó el token o sus permisos.');
        }

        if (! $response->successful()) {
            if ($contract === 'meshiverse' && $response->notFound()) {
                return $this->failed(
                    $baseUrl,
                    'El proyecto respondió, pero no tiene una ruta de conexión compatible. Se probaron POST /api/v1/connections/handshake y GET /api/v1/partner/connection.'
                );
            }

            return $this->failed($baseUrl, "El otro proyecto respondió con el código HTTP {$response->status()}.");
        }

        $payload = $response->json();
        $connectionResult = is_array($payload)
            ? $this->connectionResult($contract, $payload, $baseUrl)
            : null;

        if ($connectionResult === null) {
            return $this->failed($baseUrl, "La ruta {$endpoint} respondió, pero no confirmó una conexión autenticada.");
        }

        return to_route('api.docs')->with('connection_result', $connectionResult);
    }

    /** @return array{Response, string, string} */
    private function probe(string $baseUrl, string $partnerToken): array
    {
        $hamaEndpoint = $baseUrl.'/api/v1/connections/handshake';
        $response = Http::acceptJson()
            ->asJson()
            ->withToken($partnerToken)
            ->connectTimeout(3)
            ->timeout(8)
            ->post($hamaEndpoint, [
                'project_name' => config('app.name', 'MeshiVerse'),
            ]);

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

    private function failed(string $baseUrl, string $message): RedirectResponse
    {
        return to_route('api.docs')
            ->withErrors(['partner_connection' => $message])
            ->withInput(['partner_url' => $baseUrl]);
    }
}
