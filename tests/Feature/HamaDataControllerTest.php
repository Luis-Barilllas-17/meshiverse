<?php

namespace Tests\Feature;

use App\Models\PartnerConnection;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HamaDataControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_sees_connection_form_when_hama_token_has_not_been_saved(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.api.hamas.index'))
            ->assertOk()
            ->assertSee('Pega una vez el token Hama')
            ->assertSee('Verificar, guardar y traer los Hama');
    }

    public function test_regular_users_cannot_open_hama_data(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.api.hamas.index'))
            ->assertForbidden();
    }

    public function test_admin_receives_and_sees_every_hama_field_from_the_remote_api(): void
    {
        $accessToken = str_repeat('h', 80);
        $connection = PartnerConnection::factory()->create([
            'access_token' => $accessToken,
            'records_count' => 0,
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'http://127.0.0.1:8000/api/v1/hamas' => Http::response([
                'success' => true,
                'data' => [[
                    'id' => 27,
                    'nombre' => 'Dragón azul',
                    'descripcion' => 'Diseño guardado en el proyecto Hama.',
                    'ancho' => 12,
                    'alto' => 10,
                    'cantidad_cuentas' => 3,
                    'color_principal' => '#2563eb',
                    'es_publico' => true,
                    'patron' => [
                        ['posicion' => 1, 'color' => '#2563eb'],
                        ['posicion' => 2, 'color' => '#ffffff'],
                        ['posicion' => 13, 'color' => '#2563eb'],
                    ],
                    'created_at' => '2026-10-30T12:00:00+00:00',
                    'updated_at' => '2026-10-31T15:30:00+00:00',
                ]],
                'meta' => [
                    'resource' => 'hamas',
                    'api_version' => 'v1',
                    'count' => 1,
                    'visibility' => 'public_only',
                    'generated_at' => '2026-10-31T16:00:00+00:00',
                ],
            ]),
        ]);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.api.hamas.index'))
            ->assertOk()
            ->assertSee('Transferencia comprobada')
            ->assertSee('1 Hama recibidos desde ProyectoAPI')
            ->assertSee('Dragón azul')
            ->assertSee('Diseño guardado en el proyecto Hama.')
            ->assertSee('12 × 10')
            ->assertSee('#2563eb')
            ->assertSee('Ver JSON transferido');

        Http::assertSent(fn (Request $request): bool => $request->url() === 'http://127.0.0.1:8000/api/v1/hamas'
            && $request->method() === 'GET'
            && $request->hasHeader('Authorization', 'Bearer '.$accessToken)
        );

        $connection->refresh();

        $this->assertSame('connected', $connection->last_status);
        $this->assertSame(1, $connection->records_count);
        $this->assertNotNull($connection->last_synced_at);
        $this->assertNull($connection->last_error);
    }

    public function test_admin_sees_when_the_saved_token_is_rejected(): void
    {
        $connection = PartnerConnection::factory()->create();

        Http::preventStrayRequests();
        Http::fake([
            'http://127.0.0.1:8000/api/v1/hamas' => Http::response([
                'message' => 'Unauthenticated.',
            ], 401),
        ]);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.api.hamas.index'))
            ->assertOk()
            ->assertSee('ProyectoAPI respondió con un problema')
            ->assertSee('rechazó el token');

        $connection->refresh();

        $this->assertSame('rejected', $connection->last_status);
        $this->assertNotNull($connection->last_error);
    }

    public function test_admin_can_forget_the_saved_hama_connection(): void
    {
        PartnerConnection::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.api.hamas.connection.destroy'))
            ->assertRedirect(route('admin.api.hamas.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseEmpty('partner_connections');
    }
}
