<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_user_with_profile_scope_can_read_their_profile(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user, ['profile:read']);

        $this->getJson(route('api.v1.account.me'))
            ->assertOk()
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.scopes.0', 'profile:read');
    }

    public function test_profile_endpoint_rejects_a_token_without_required_scope(): void
    {
        Passport::actingAs(User::factory()->create(), ['recipes:read']);

        $this->getJson(route('api.v1.account.me'))->assertForbidden();
    }

    public function test_user_can_read_recipes_and_logout(): void
    {
        Recipe::factory()->create(['title' => 'Receta privada']);
        Passport::actingAs(User::factory()->create(), ['recipes:read']);

        $this->getJson(route('api.v1.account.recipes'))
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Receta privada');

        $this->deleteJson(route('api.v1.account.logout'))
            ->assertOk()
            ->assertJsonPath('message', 'Sesión cerrada correctamente.');
    }
}
