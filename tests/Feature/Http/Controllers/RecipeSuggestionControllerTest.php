<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class RecipeSuggestionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('suggestions.create'))->assertRedirect(route('login'));
        $this->post(route('suggestions.store'), [])->assertRedirect(route('login'));
    }

    public function test_suggestion_form_is_available_to_an_authenticated_user(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('suggestions.create'))
            ->assertOk()
            ->assertSee('Sugiere el próximo platillo');
    }

    public function test_user_can_submit_a_suggestion_from_the_site(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('suggestions.store'), [
            'dish_name' => 'Pan de aventura',
            'source_title' => 'Dr. Stone',
            'source_type' => 'anime',
            'email' => '',
        ])->assertRedirect(route('suggestions.create'));

        $this->assertDatabaseHas('recipe_suggestions', [
            'user_id' => $user->id,
            'dish_name' => 'Pan de aventura',
            'source_title' => 'Dr. Stone',
            'email' => $user->email,
        ]);
    }
}
