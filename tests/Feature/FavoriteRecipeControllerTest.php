<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class FavoriteRecipeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_cannot_manage_favorites(): void
    {
        $recipe = Recipe::factory()->create();

        $this->post(route('favorites.store', $recipe))->assertRedirect(route('login'));
        $this->get(route('favorites.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_add_and_remove_a_published_recipe_from_favorites(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $this->actingAs($user)
            ->post(route('favorites.store', $recipe))
            ->assertRedirect();

        $this->assertDatabaseHas('favorite_recipes', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);

        $this->get(route('favorites.index'))
            ->assertOk()
            ->assertSee($recipe->title);

        $this->delete(route('favorites.destroy', $recipe))->assertRedirect();
        $this->assertDatabaseMissing('favorite_recipes', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_user_cannot_favorite_an_unpublished_recipe(): void
    {
        $recipe = Recipe::factory()->draft()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('favorites.store', $recipe))
            ->assertNotFound();
    }
}
