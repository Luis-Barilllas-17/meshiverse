<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IntegrationProbeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_it_confirms_a_successful_hama_connection(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'http://127.0.0.1:8000/api/v1/connections/handshake' => Http::response([
                'success' => true,
                'message' => 'Conexión autenticada correctamente con ProyectoAPI.',
                'connection' => [
                    'status' => 'connected',
                    'provider' => 'ProyectoAPI',
                ],
                'capabilities' => [
                    ['label' => 'Consultar proyectos Hama guardados', 'allowed' => true],
                    ['label' => 'Crear un Hama', 'allowed' => false],
                ],
            ]),
        ]);

        $this->post(route('api.connections.test'), [
            'partner_url' => 'http://127.0.0.1:8000',
            'partner_token' => 'Bearer '.str_repeat('a', 80),
        ])
            ->assertRedirect(route('api.docs'))
            ->assertSessionHas('connection_result.status', 'connected')
            ->assertSessionHas('connection_result.service', 'ProyectoAPI')
            ->assertSessionHas('connection_result.sharing', ['Consultar proyectos Hama guardados']);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'http://127.0.0.1:8000/api/v1/connections/handshake'
            && $request->method() === 'POST'
            && $request->hasHeader('Authorization', 'Bearer '.str_repeat('a', 80))
            && $request->data()['project_name'] === config('app.name')
        );
    }

    public function test_it_falls_back_to_the_meshiverse_connection_contract(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'http://127.0.0.1:8000/api/v1/connections/handshake' => Http::response([], 404),
            'http://127.0.0.1:8000/api/v1/partner/connection' => Http::response([
                'status' => 'connected',
                'service' => 'MeshiVerse asociado',
                'message' => 'Conexión lista.',
                'sharing' => [
                    'dish_of_the_day' => ['label' => 'Platillo del día'],
                    'recipe_suggestions' => ['label' => 'Encuesta de propuestas de comida'],
                ],
                'preview' => ['dish_of_the_day' => ['title' => 'Ramen de prueba']],
            ]),
        ]);

        $this->post(route('api.connections.test'), [
            'partner_url' => 'http://127.0.0.1:8000',
            'partner_token' => str_repeat('a', 80),
        ])
            ->assertRedirect(route('api.docs'))
            ->assertSessionHas('connection_result.status', 'connected')
            ->assertSessionHas('connection_result.service', 'MeshiVerse asociado')
            ->assertSessionHas('connection_result.dish_title', 'Ramen de prueba');

        Http::assertSentCount(2);
    }

    public function test_it_reports_when_the_partner_rejects_the_token_without_flashing_it(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'http://127.0.0.1:8000/api/v1/connections/handshake' => Http::response([], 401),
        ]);

        $this->post(route('api.connections.test'), [
            'partner_url' => 'http://127.0.0.1:8000',
            'partner_token' => str_repeat('s', 80),
        ])
            ->assertRedirect(route('api.docs'))
            ->assertSessionHasErrors(['partner_connection'])
            ->assertSessionHas('_old_input', fn (array $input): bool => ! array_key_exists('partner_token', $input));

        Http::assertSentCount(1);
    }

    public function test_it_explains_when_the_partner_has_no_compatible_connection_route(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'http://127.0.0.1:8000/api/v1/connections/handshake' => Http::response([], 404),
            'http://127.0.0.1:8000/api/v1/partner/connection' => Http::response([], 404),
        ]);

        $this->post(route('api.connections.test'), [
            'partner_url' => 'http://127.0.0.1:8000',
            'partner_token' => str_repeat('a', 80),
        ])
            ->assertRedirect(route('api.docs'))
            ->assertSessionHasErrors([
                'partner_connection' => 'El proyecto respondió, pero no tiene una ruta de conexión compatible. Se probaron POST /api/v1/connections/handshake y GET /api/v1/partner/connection.',
            ]);
    }

    public function test_partner_url_cannot_contain_embedded_credentials(): void
    {
        $this->post(route('api.connections.test'), [
            'partner_url' => 'http://admin:secret@127.0.0.1:8000',
            'partner_token' => str_repeat('a', 80),
        ])
            ->assertSessionHasErrors(['partner_url'])
            ->assertSessionHas('_old_input', fn (array $input): bool => ! array_key_exists('partner_token', $input));
    }
}
