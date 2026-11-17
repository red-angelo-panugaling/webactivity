<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_management_page(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route('admin.users.index'))
            ->assertSee('Total Users')
            ->assertSee('Add User')
            ->assertSee('User ID');
    }

    public function test_admin_can_create_user_with_hashed_password(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'full_name' => 'Sample User',
            'username' => 'sample-user',
            'email' => 'sample@example.com',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
            'role' => User::ROLE_USER,
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'full_name' => 'Sample User',
            'username' => 'sample-user',
            'email' => 'sample@example.com',
            'role' => User::ROLE_USER,
            'status' => true,
        ]);
        $this->assertDatabaseMissing('users', ['password' => 'StrongPass123']);
    }

    public function test_regular_user_cannot_access_user_management(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_user_dashboard_does_not_show_admin_navigation(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertSee('User Dashboard')
            ->assertDontSee('User Management');
    }

    public function test_admin_can_update_account_and_change_password(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'full_name' => 'Updated Name',
            'username' => 'updated-name',
            'email' => 'updated@example.com',
            'password' => 'NewStrongPass123',
            'password_confirmation' => 'NewStrongPass123',
            'role' => User::ROLE_ADMIN,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'full_name' => 'Updated Name',
            'username' => 'updated-name',
            'email' => 'updated@example.com',
            'role' => User::ROLE_ADMIN,
        ]);
        $this->assertNotSame('NewStrongPass123', $user->fresh()->password);
    }

    public function test_deactivated_user_is_logged_out_on_next_request(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        User::whereKey($user->id)->update(['status' => false]);

        $this->get(route('dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('danger', 'Your account is currently inactive. Please contact the system administrator for assistance.');

        $this->assertGuest();
    }

    public function test_admin_can_activate_and_deactivate_accounts(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($admin)->patch(route('admin.users.status', $user), ['status' => 'inactive'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => false]);

        $this->patch(route('admin.users.status', $user), ['status' => 'active'])->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => true]);
    }
}
