<?php

namespace Tests\Feature\Http\Controllers;

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

    public function test_suggestion_form_is_available(): void
    {
        $this->get(route('suggestions.create'))
            ->assertOk()
            ->assertSee('¿Qué platillo debe entrar');
    }

    public function test_guest_can_submit_a_suggestion_from_the_site(): void
    {
        $this->post(route('suggestions.store'), [
            'dish_name' => 'Pan de aventura',
            'source_title' => 'Dr. Stone',
            'source_type' => 'anime',
            'email' => 'fan@example.com',
        ])->assertRedirect(route('suggestions.create'));

        $this->assertDatabaseHas('recipe_suggestions', [
            'dish_name' => 'Pan de aventura',
            'source_title' => 'Dr. Stone',
        ]);
    }
}
