<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class RecipePageControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_page_displays_the_recipe_catalog(): void
    {
        $series = AnimeSeries::factory()->create([
            'name' => 'One Piece',
            'icon' => 'ICON_SHOULD_NOT_RENDER',
            'image_path' => 'images/series/one-piece.jpg',
        ]);
        $recipe = Recipe::factory()->for($series)->create(['title' => 'Arroz pirata']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('MeshiVerse')
            ->assertSee('Arroz pirata')
            ->assertSee('One Piece')
            ->assertSee('images/series/one-piece.jpg', false)
            ->assertDontSee('ICON_SHOULD_NOT_RENDER');
    }

    public function test_home_page_filters_recipes_by_anime(): void
    {
        $onePiece = AnimeSeries::factory()->create(['slug' => 'one-piece']);
        $dungeon = AnimeSeries::factory()->create(['slug' => 'delicious-in-dungeon']);

        Recipe::factory()->for($onePiece)->create(['title' => 'Arroz del mar']);
        Recipe::factory()->for($dungeon)->create(['title' => 'Curry del calabozo']);

        $this->get(route('home', ['anime' => 'one-piece']))
            ->assertOk()
            ->assertSee('Arroz del mar')
            ->assertDontSee('Curry del calabozo');
    }

    public function test_recipe_detail_displays_ingredients_and_steps(): void
    {
        $recipe = Recipe::factory()->create([
            'title' => 'Banquete de prueba',
            'ingredients' => ['Arroz brillante', 'Sal marina'],
            'steps' => ['Mezcla todos los ingredientes.', 'Sirve caliente.'],
        ]);

        $this->get(route('recipes.show', $recipe))
            ->assertOk()
            ->assertSee('Banquete de prueba')
            ->assertSee('Arroz brillante')
            ->assertSee('🍚')
            ->assertSee('Mezcla todos los ingredientes.');
    }

    public function test_home_page_displays_the_dish_of_the_day(): void
    {
        Recipe::factory()->create(['title' => 'Platillo diario de prueba']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Platillo del día')
            ->assertSee('Platillo diario de prueba');
    }
}
