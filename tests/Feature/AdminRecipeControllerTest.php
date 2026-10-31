<?php

namespace Tests\Feature;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRecipeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login_and_regular_user_is_forbidden(): void
    {
        $this->get(route('admin.recipes.create'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.recipes.create'))
            ->assertForbidden();
    }

    public function test_admin_can_open_the_recipe_form(): void
    {
        AnimeSeries::factory()->create(['name' => 'Delicious in Dungeon']);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.recipes.create'))
            ->assertOk()
            ->assertSee('Crear receta')
            ->assertSee('Delicious in Dungeon');
    }

    public function test_admin_can_create_and_publish_a_recipe(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $series = AnimeSeries::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.recipes.store'), [
                'anime_series_id' => $series->id,
                'title' => 'Estofado del calabozo',
                'slug' => '',
                'subtitle' => 'Una cena para aventureros',
                'description' => 'Un estofado abundante inspirado en una expedición fantástica.',
                'image' => UploadedFile::fake()->image('estofado.jpg', 1200, 900),
                'source_label' => 'Delicious in Dungeon',
                'ingredients' => ['500 g de carne', '2 zanahorias'],
                'steps' => ['Corta todos los ingredientes.', 'Cocina lentamente hasta espesar.'],
                'servings' => 4,
                'prep_minutes' => 20,
                'cook_minutes' => 60,
                'difficulty' => 'media',
                'is_featured' => '1',
                'publish_now' => '1',
            ])
            ->assertRedirect(route('admin.recipes.create'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('recipes', [
            'title' => 'Estofado del calabozo',
            'slug' => 'estofado-del-calabozo',
            'anime_series_id' => $series->id,
            'is_featured' => true,
        ]);

        $recipe = Recipe::query()->where('slug', 'estofado-del-calabozo')->firstOrFail();
        Storage::disk('public')->assertExists(str_replace('storage/', '', $recipe->image_path));
    }
}
