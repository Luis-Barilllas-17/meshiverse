<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_recipe_api_requires_an_access_token(): void
    {
        $this->getJson(route('api.v1.recipes.index'))->assertUnauthorized();
    }

    public function test_client_with_read_scope_can_list_recipes(): void
    {
        $recipe = Recipe::factory()->create(['title' => 'Curry de prueba']);
        $this->actingAsClient(['recipes:read']);

        $this->getJson(route('api.v1.recipes.index'))
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Curry de prueba')
            ->assertJsonPath('data.0.anime.id', $recipe->animeSeries->id);
    }

    public function test_client_without_write_scope_cannot_create_a_recipe(): void
    {
        $this->actingAsClient(['recipes:read']);

        $this->postJson(route('api.v1.recipes.store'), [])->assertForbidden();
    }

    public function test_scoped_clients_can_create_update_and_delete_a_recipe(): void
    {
        $series = AnimeSeries::factory()->create();

        $this->actingAsClient(['recipes:write']);

        $created = $this->postJson(route('api.v1.recipes.store'), $this->payload($series))
            ->assertCreated()
            ->assertJsonPath('data.title', 'Ramen de integración')
            ->json('data');

        $recipe = Recipe::query()->where('slug', $created['slug'])->firstOrFail();

        $this->patchJson(route('api.v1.recipes.update', $recipe), ['title' => 'Ramen actualizado'])
            ->assertOk()
            ->assertJsonPath('data.title', 'Ramen actualizado');

        $this->actingAsClient(['recipes:delete']);

        $this->deleteJson(route('api.v1.recipes.destroy', $recipe))->assertNoContent();
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_create_recipe_returns_validation_errors(): void
    {
        $this->actingAsClient(['recipes:write']);

        $this->postJson(route('api.v1.recipes.store'), ['title' => 'Incompleta'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['anime_series_id', 'description', 'ingredients', 'steps']);
    }

    /** @param string[] $scopes */
    private function actingAsClient(array $scopes): void
    {
        $client = app(ClientRepository::class)->createClientCredentialsGrantClient('Test integration');

        Passport::actingAsClient($client, $scopes);
    }

    /** @return array<string, mixed> */
    private function payload(AnimeSeries $series): array
    {
        return [
            'anime_series_id' => $series->id,
            'title' => 'Ramen de integración',
            'description' => 'Una receta creada desde otro proyecto.',
            'image_path' => 'images/recipes/d436a80e-ec0f-4ad7-a334-0312858f8b46.jpg',
            'ingredients' => ['400 g de fideos', '500 ml de caldo'],
            'steps' => ['Hierve el caldo.', 'Añade los fideos y sirve.'],
            'servings' => 2,
            'prep_minutes' => 10,
            'cook_minutes' => 20,
            'difficulty' => 'fácil',
        ];
    }
}
