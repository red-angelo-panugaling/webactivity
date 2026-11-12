<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_and_links_to_the_dashboard(): void
    {
        $response = $this->get(route('login'));

        $response->assertSee('id="inputEmail"', false);
        $response->assertSee('id="inputPassword"', false);
        $response->assertSee('action="'.route('process_login').'"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_active_user_can_log_in_and_view_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email' => 'admin@example.com',
            'password' => 'Admin@12345',
        ]);

        $this->withSession(['url.intended' => '/index.html'])->post(route('process_login'), [
            'email' => 'admin@example.com',
            'password' => 'Admin@12345',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_regular_user_can_log_in_and_is_sent_to_the_user_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
            'email' => 'user@example.com',
            'password' => 'UserPass123',
        ]);

        $this->post(route('process_login'), [
            'email' => 'user@example.com',
            'password' => 'UserPass123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('dashboard'))->assertSee('User Dashboard');
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => 'Admin@12345',
            'status' => false,
        ]);

        $response = $this->from(route('login'))->post(route('process_login'), [
            'email' => 'inactive@example.com',
            'password' => 'Admin@12345',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionHas('danger', 'Your account is currently inactive. Please contact the system administrator for assistance.');
    }

    public function test_login_requires_a_valid_email_and_password(): void
    {
        $this->from(route('login'))->post(route('process_login'), [
            'email' => 'invalid-email',
            'password' => '',
        ])->assertSessionHasErrors(['email', 'password']);
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
