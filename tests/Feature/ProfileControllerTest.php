<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_authenticated_user_can_update_profile_information(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Marcille Donato',
                'email' => 'marcille@example.com',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Marcille Donato',
            'email' => 'marcille@example.com',
        ]);
    }

    public function test_authenticated_user_can_change_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('profile.password.update'), [
                'current_password' => 'password',
                'password' => 'nueva-clave-segura',
                'password_confirmation' => 'nueva-clave-segura',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('nueva-clave-segura', $user->refresh()->password));
    }

    public function test_current_password_is_required_to_change_the_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('profile.password.update'), [
                'current_password' => 'incorrecta',
                'password' => 'nueva-clave-segura',
                'password_confirmation' => 'nueva-clave-segura',
            ])
            ->assertSessionHasErrors(['current_password']);
    }
}
