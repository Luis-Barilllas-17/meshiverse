<?php

namespace Tests\Feature;

use App\Models\DishOfTheDaySetting;
use App\Models\Recipe;
use App\Models\User;
use App\Support\DishOfTheDay;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DishOfTheDayControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_regular_user_cannot_manage_the_dish_of_the_day(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dish.index'))
            ->assertForbidden();
    }

    public function test_admin_can_select_a_draft_recipe_manually(): void
    {
        $admin = User::factory()->admin()->create();
        $recipe = Recipe::factory()->draft()->create();

        $this->actingAs($admin)
            ->patch(route('admin.dish.update'), [
                'mode' => 'manual',
                'recipe_id' => $recipe->id,
            ])
            ->assertRedirect(route('admin.dish.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('dish_of_the_day_settings', [
            'mode' => 'manual',
            'recipe_id' => $recipe->id,
            'updated_by' => $admin->id,
        ]);
        $this->assertNotNull($recipe->refresh()->published_at);
        $this->assertTrue(app(DishOfTheDay::class)->get()?->is($recipe));
    }

    public function test_admin_can_restore_automatic_selection(): void
    {
        $admin = User::factory()->admin()->create();
        $recipe = Recipe::factory()->create();
        DishOfTheDaySetting::query()->create([
            'mode' => 'manual',
            'recipe_id' => $recipe->id,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.dish.update'), ['mode' => 'automatic'])
            ->assertRedirect(route('admin.dish.index'));

        $this->assertDatabaseHas('dish_of_the_day_settings', [
            'mode' => 'automatic',
            'recipe_id' => null,
        ]);
    }
}
