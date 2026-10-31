<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_can_open_the_login_page(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Iniciar sesión');
    }

    public function test_regular_user_is_redirected_to_the_user_dashboard_after_login(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('user.dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_is_redirected_to_the_admin_dashboard_after_login(): void
    {
        $admin = User::factory()->admin()->create();

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_credentials_do_not_authenticate_the_user(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'incorrecta',
        ])->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
