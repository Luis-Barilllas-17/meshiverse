<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRemoteHamaRequest;
use App\Models\PartnerConnection;
use App\Support\HamaApiClient;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HamaDataController extends Controller
{
    public function index(HamaApiClient $hamaClient): View
    {
        $connection = $this->connection();

        if ($connection === null) {
            return $this->view();
        }

        try {
            $response = $hamaClient->hamas($connection->base_url, $connection->access_token);
        } catch (ConnectionException) {
            return $this->failed(
                $connection,
                'offline',
                'No fue posible contactar ProyectoAPI. Confirma que el proyecto Hama esté encendido y que conserve la misma URL.',
            );
        } catch (DecryptException) {
            return $this->failed(
                $connection,
                'invalid_token',
                'El token guardado ya no puede descifrarse. Elimínalo y conecta ProyectoAPI nuevamente.',
            );
        }

        if ($response->unauthorized() || $response->forbidden()) {
            return $this->failed(
                $connection,
                'rejected',
                'ProyectoAPI respondió, pero rechazó el token. Genera uno nuevo con el permiso hamas.read.',
            );
        }

        if (! $response->successful()) {
            return $this->failed(
                $connection,
                'error',
                "ProyectoAPI respondió con el código HTTP {$response->status()} al consultar /api/v1/hamas.",
            );
        }

        $payload = $response->json();

        if (! is_array($payload) || data_get($payload, 'success') !== true || ! is_array(data_get($payload, 'data'))) {
            return $this->failed(
                $connection,
                'invalid_response',
                'ProyectoAPI respondió, pero el contenido no coincide con el contrato de datos Hama.',
            );
        }

        $hamas = collect(data_get($payload, 'data'))
            ->filter(fn (mixed $hama): bool => is_array($hama))
            ->values();
        $meta = is_array(data_get($payload, 'meta')) ? data_get($payload, 'meta') : [];

        $connection->update([
            'last_status' => 'connected',
            'records_count' => $hamas->count(),
            'last_verified_at' => now(),
            'last_synced_at' => now(),
            'last_error' => null,
        ]);

        return $this->view($connection->fresh(), $hamas, $meta);
    }

    public function store(StoreRemoteHamaRequest $request, HamaApiClient $hamaClient): RedirectResponse
    {
        $connection = $this->connection();

        if ($connection === null) {
            return to_route('admin.api.hamas.index')
                ->withErrors(['hama_creation' => 'Primero conecta ProyectoAPI con un token Hama.'])
                ->withInput();
        }

        if (! $this->canCreateHamas($connection)) {
            return to_route('admin.api.hamas.index')
                ->withErrors(['hama_creation' => 'El token guardado permite leer, pero no crear Hama. Genera uno nuevo con hamas.read y hamas.write.'])
                ->withInput();
        }

        try {
            $response = $hamaClient->createHama(
                $connection->base_url,
                $connection->access_token,
                $request->hamaPayload(),
            );
        } catch (ConnectionException) {
            return $this->creationFailed($connection, 'No fue posible contactar ProyectoAPI para crear el Hama.');
        } catch (DecryptException) {
            return $this->creationFailed($connection, 'El token guardado ya no puede descifrarse. Conecta ProyectoAPI nuevamente.');
        }

        if ($response->unauthorized() || $response->forbidden()) {
            return $this->creationFailed(
                $connection,
                'ProyectoAPI rechazó la creación. Genera un token nuevo con el permiso hamas.write.',
                'rejected',
            );
        }

        if ($response->unprocessableEntity()) {
            return $this->creationFailed($connection, $this->remoteValidationMessage($response->json()));
        }

        if (! $response->successful() || data_get($response->json(), 'success') !== true) {
            return $this->creationFailed(
                $connection,
                "ProyectoAPI respondió con el código HTTP {$response->status()} y no creó el Hama.",
            );
        }

        $connection->update([
            'last_status' => 'connected',
            'last_verified_at' => now(),
            'last_error' => null,
        ]);

        $createdName = data_get($response->json(), 'data.nombre', $request->string('nombre')->toString());

        return to_route('admin.api.hamas.index')
            ->with('success', "Hama «{$createdName}» creado correctamente en ProyectoAPI.");
    }

    public function destroy(): RedirectResponse
    {
        PartnerConnection::query()
            ->where('provider', PartnerConnection::PROVIDER_HAMA)
            ->delete();

        return to_route('admin.api.hamas.index')
            ->with('success', 'La conexión Hama y su token cifrado fueron eliminados de MeshiVerse.');
    }

    private function connection(): ?PartnerConnection
    {
        return PartnerConnection::query()
            ->where('provider', PartnerConnection::PROVIDER_HAMA)
            ->first();
    }

    /**
     * @param  Collection<int, array<string, mixed>>|null  $hamas
     * @param  array<string, mixed>  $meta
     */
    private function view(
        ?PartnerConnection $connection = null,
        ?Collection $hamas = null,
        array $meta = [],
        ?string $connectionError = null,
    ): View {
        return view('admin.hama-data', [
            'connection' => $connection,
            'hamas' => $hamas ?? collect(),
            'meta' => $meta,
            'connectionError' => $connectionError,
            'canCreateHamas' => $connection !== null && $this->canCreateHamas($connection),
        ]);
    }

    private function failed(PartnerConnection $connection, string $status, string $message): View
    {
        $connection->update([
            'last_status' => $status,
            'last_error' => $message,
        ]);

        return $this->view($connection->fresh(), connectionError: $message);
    }

    private function creationFailed(
        PartnerConnection $connection,
        string $message,
        string $status = 'error',
    ): RedirectResponse {
        $connection->update([
            'last_status' => $status,
            'last_error' => $message,
        ]);

        return to_route('admin.api.hamas.index')
            ->withErrors(['hama_creation' => $message])
            ->withInput();
    }

    private function canCreateHamas(PartnerConnection $connection): bool
    {
        $hasCapability = collect($connection->capabilities ?? [])
            ->contains(fn (mixed $capability): bool => is_array($capability)
                && ($capability['key'] ?? null) === 'hamas.create'
                && ($capability['allowed'] ?? false) === true);

        return $hasCapability
            || in_array('*', $connection->scopes ?? [], true)
            || in_array('hamas.write', $connection->scopes ?? [], true);
    }

    private function remoteValidationMessage(mixed $payload): string
    {
        if (! is_array($payload)) {
            return 'ProyectoAPI rechazó los datos del Hama.';
        }

        $details = collect(data_get($payload, 'errors', []))
            ->flatten()
            ->filter(fn (mixed $message): bool => is_string($message) && $message !== '')
            ->implode(' ');

        return $details !== ''
            ? 'ProyectoAPI rechazó los datos: '.$details
            : (string) data_get($payload, 'message', 'ProyectoAPI rechazó los datos del Hama.');
    }
}
