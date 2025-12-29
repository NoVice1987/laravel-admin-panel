<?php

namespace StatisticLv\AdminPanel\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use StatisticLv\AdminPanel\Models\AdminUser;
use StatisticLv\AdminPanel\Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_login_page_can_be_accessed()
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-panel::auth.login');
    }

    /** @test */
    public function admin_can_login_with_correct_credentials()
    {
        $admin = AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@test.com',
            'password' => 'Password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    /** @test */
    public function admin_cannot_login_with_incorrect_credentials()
    {
        AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    /** @test */
    public function admin_cannot_login_when_account_is_inactive()
    {
        AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@test.com',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    /** @test */
    public function login_is_rate_limited()
    {
        $admin = AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Attempt 5 failed logins
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.post'), [
                'email' => 'admin@test.com',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@test.com',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    /** @test */
    public function unauthenticated_admin_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_dashboard()
    {
        $admin = AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-panel::dashboard.index');
    }

    /** @test */
    public function inactive_admin_cannot_access_dashboard()
    {
        $admin = AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function admin_can_logout()
    {
        $admin = AdminUser::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    /** @test */
    public function email_field_is_required()
    {
        $response = $this->post(route('admin.login.post'), [
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function password_field_is_required()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@test.com',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function email_must_be_valid()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'invalid-email',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
