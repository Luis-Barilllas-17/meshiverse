<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PartnerConnectionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_connection_endpoint_requires_a_token(): void
    {
        $this->getJson(route('api.v1.partner.connection'))->assertUnauthorized();
    }

    public function test_connection_endpoint_rejects_a_token_without_connection_scope(): void
    {
        Passport::actingAs(User::factory()->create(), ['dish-of-the-day:read']);

        $this->getJson(route('api.v1.partner.connection'))->assertForbidden();
    }

    public function test_scoped_token_confirms_connection_and_shared_data(): void
    {
        Recipe::factory()->create(['title' => 'Curry de conexión']);
        Passport::actingAs(User::factory()->create(), ['integration:connect']);

        $this->getJson(route('api.v1.partner.connection'))
            ->assertOk()
            ->assertJsonPath('status', 'connected')
            ->assertJsonPath('service', config('app.name'))
            ->assertJsonPath('sharing.dish_of_the_day.scope', 'dish-of-the-day:read')
            ->assertJsonPath('sharing.recipe_suggestions.scope', 'suggestions:create')
            ->assertJsonPath('preview.dish_of_the_day.title', 'Curry de conexión');
    }

    public function test_partner_can_read_daily_dish_with_its_scope(): void
    {
        Recipe::factory()->create(['title' => 'Dango compartido']);
        Passport::actingAs(User::factory()->create(), ['dish-of-the-day:read']);

        $this->getJson(route('api.v1.partner.dish-of-the-day'))
            ->assertOk()
            ->assertJsonPath('data.title', 'Dango compartido');
    }

    public function test_partner_can_send_a_suggestion_with_its_scope(): void
    {
        Passport::actingAs(User::factory()->create(), ['suggestions:create']);

        $this->postJson(route('api.v1.partner.suggestions.store'), [
            'dish_name' => 'Pastel de videojuego',
            'source_title' => 'Portal',
            'source_type' => 'videojuego',
            'reason' => 'Sería una propuesta divertida.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('recipe_suggestions', [
            'dish_name' => 'Pastel de videojuego',
            'source_title' => 'Portal',
        ]);
    }
}
