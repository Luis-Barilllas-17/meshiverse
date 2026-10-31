<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use App\Support\IntegrationTokenManager;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class IntegrationDashboardControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_local_user_can_open_the_connection_center(): void
    {
        Recipe::factory()->create(['title' => 'Platillo para compartir']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.api.docs'))
            ->assertOk()
            ->assertSee('Centro de conexión local')
            ->assertSee('Platillo para compartir');
    }

    public function test_remote_request_cannot_open_the_connection_center(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->get(route('admin.api.docs'))
            ->assertForbidden();
    }

    public function test_regular_user_cannot_open_the_connection_center(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.api.docs'))
            ->assertForbidden();
    }

    public function test_local_user_can_issue_and_revoke_a_scoped_partner_token(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.api.tokens.store'), [
            'token_name' => 'Proyecto Hama',
        ]);

        $response
            ->assertRedirect(route('admin.api.docs'))
            ->assertSessionHas('generated_token', fn (string $token): bool => strlen($token) > 100);

        $client = Passport::client()->newQuery()->latest()->firstOrFail();
        $token = Passport::token()->newQuery()->latest()->firstOrFail();

        $this->assertTrue($client->hasGrantType('personal_access'));
        $this->assertSame(IntegrationTokenManager::SCOPES, $token->scopes);
        $this->assertFalse($token->revoked);

        $this->delete(route('admin.api.tokens.destroy', $token->id))
            ->assertRedirect(route('admin.api.docs'));

        $this->assertTrue($token->refresh()->revoked);
    }

    public function test_token_name_is_required(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.api.tokens.store'), [])
            ->assertSessionHasErrors(['token_name']);
    }
}
