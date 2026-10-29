<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PublicRecipeFeaturesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dish_of_the_day_is_public_and_stable_during_the_day(): void
    {
        Recipe::factory()->count(3)->create();

        $firstRecipeId = $this->getJson(route('api.v1.dish-of-the-day'))
            ->assertOk()
            ->assertJsonPath('meta.selection', 'dish-of-the-day')
            ->json('data.id');

        $this->getJson(route('api.v1.dish-of-the-day'))
            ->assertOk()
            ->assertJsonPath('data.id', $firstRecipeId);
    }

    public function test_guest_can_submit_a_recipe_suggestion_through_the_api(): void
    {
        $this->postJson(route('api.v1.suggestions.store'), [
            'dish_name' => 'Dango especial',
            'source_title' => 'Monster Hunter Rise',
            'source_type' => 'videojuego',
            'reason' => 'Debe formar parte del menú de Kamura.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('recipe_suggestions', [
            'dish_name' => 'Dango especial',
            'source_type' => 'videojuego',
            'status' => 'pending',
        ]);
    }

    public function test_suggestion_api_validates_required_fields(): void
    {
        $this->postJson(route('api.v1.suggestions.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['dish_name', 'source_title', 'source_type']);
    }
}
