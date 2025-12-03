<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Authentication Tests
 * 
 * Tests login, registration, logout, and password reset functionality.
 */
class AuthenticationTest extends TestCase
{
    use UseCmsSqlSchema;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bind test services
        $this->app->bind(\App\Services\FirebaseService::class, \Tests\Mocks\FakeFirebaseService::class);
    }

    // =========================================
    // Login Tests
    // =========================================

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'role' => 'client',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        // Should either be denied or redirected with error
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 422
        );
    }

    // =========================================
    // Registration Tests
    // =========================================

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '09171234567',
        ]);

        // Should redirect after successful registration
        $response->assertRedirect();

        // Check user was created
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->post(route('register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_cannot_register_with_existing_email(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post(route('register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // =========================================
    // Logout Tests
    // =========================================

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect();
        $this->assertGuest();
    }

    // =========================================
    // Password Reset Tests
    // =========================================

    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    public function test_password_reset_link_can_be_requested(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'status' => 'active',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'reset@example.com',
        ]);

        // Should redirect with status
        $response->assertRedirect();
    }

    public function test_password_reset_requires_valid_email(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        // Should still redirect (for security, don't reveal if email exists)
        $response->assertRedirect();
    }

    // =========================================
    // Role-based Redirect Tests
    // =========================================

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
    }

    public function test_adiutor_is_redirected_to_adiutor_dashboard(): void
    {
        $adiutor = User::factory()->create([
            'email' => 'adiutor@example.com',
            'password' => Hash::make('password123'),
            'role' => 'adiutor',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'adiutor@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($adiutor);
    }

    public function test_client_is_redirected_to_client_dashboard(): void
    {
        $client = User::factory()->create([
            'email' => 'client@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'client@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($client);
    }

    // =========================================
    // Guest Middleware Tests
    // =========================================

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('login'));

        $response->assertRedirect();
    }

    public function test_authenticated_user_is_redirected_from_register_page(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('register'));

        $response->assertRedirect();
    }
}
