<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\AdiutorProfile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Admin User Management Tests
 * 
 * Tests admin functionality for managing users:
 * - Viewing users list
 * - Creating/editing users
 * - Toggling user status
 * - Managing user roles
 */
class UserManagementTest extends TestCase
{
    use UseCmsSqlSchema;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bind test services
        $this->app->bind(\App\Services\FirebaseService::class, \Tests\Mocks\FakeFirebaseService::class);

        // Create admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);
    }

    // =========================================
    // Users List Tests
    // =========================================

    public function test_admin_can_view_users_list(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_users_list(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $this->actingAs($client);

        $response = $this->get(route('admin.users.index'));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    public function test_users_list_can_filter_by_role(): void
    {
        $this->actingAs($this->admin);

        // Create users with different roles
        User::factory()->create(['role' => 'client']);
        User::factory()->create(['role' => 'adiutor']);

        $response = $this->get(route('admin.users.index', ['role' => 'client']));

        $response->assertStatus(200);
    }

    public function test_users_list_can_search(): void
    {
        $this->actingAs($this->admin);

        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $response = $this->get(route('admin.users.index', ['search' => 'john']));

        $response->assertStatus(200);
    }

    // =========================================
    // Create User Tests
    // =========================================

    public function test_admin_can_view_create_user_form(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.users.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_client_user(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.users.store'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
            'status' => 'active',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'jane.doe@example.com',
            'role' => 'client',
        ]);
    }

    public function test_admin_can_create_adiutor_user(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.users.store'), [
            'first_name' => 'Mark',
            'last_name' => 'Smith',
            'email' => 'mark.smith@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'adiutor',
            'status' => 'active',
            'bio' => 'Experienced developer',
            'hourly_rate' => 500,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'mark.smith@example.com',
            'role' => 'adiutor',
        ]);
    }

    public function test_user_creation_requires_unique_email(): void
    {
        $this->actingAs($this->admin);

        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post(route('admin.users.store'), [
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_creation_requires_password_confirmation(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.users.store'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // =========================================
    // View User Details Tests
    // =========================================

    public function test_admin_can_view_user_details(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create(['role' => 'client']);

        $response = $this->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_adiutor_profile(): void
    {
        $this->actingAs($this->admin);

        $adiutor = User::factory()->create(['role' => 'adiutor']);

        $response = $this->get(route('admin.users.show', $adiutor->id));

        $response->assertStatus(200);
    }

    // =========================================
    // Edit User Tests
    // =========================================

    public function test_admin_can_view_edit_user_form(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create(['role' => 'client']);

        $response = $this->get(route('admin.users.edit', $user->id));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_user(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'first_name' => 'Original',
            'last_name' => 'Name',
            'role' => 'client',
        ]);

        $response = $this->put(route('admin.users.update', $user->id), [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $user->email,
            'role' => 'client',
            'status' => 'active',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('Updated', $user->first_name);
    }

    public function test_admin_can_update_user_password(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'role' => 'client',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->put(route('admin.users.update', $user->id), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'role' => 'client',
            'status' => 'active',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    // =========================================
    // Delete User Tests
    // =========================================

    public function test_admin_can_delete_user(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create(['role' => 'client']);

        $response = $this->delete(route('admin.users.destroy', $user->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $this->actingAs($this->admin);

        $response = $this->delete(route('admin.users.destroy', $this->admin->id));

        // Should either get error or redirect with error message
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 422 || $response->status() === 403
        );

        // Admin should still exist
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    // =========================================
    // Toggle User Status Tests
    // =========================================

    public function test_admin_can_activate_user(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('admin.users.toggle-status', $user->id), [
            'status' => 'active',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('active', $user->status);
    }

    public function test_admin_can_deactivate_user(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $response = $this->post(route('admin.users.toggle-status', $user->id), [
            'status' => 'inactive',
            'reason' => 'Violation of terms',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('inactive', $user->status);
    }

    // =========================================
    // Adiutor Application Tests
    // =========================================

    public function test_admin_can_view_pending_adiutors(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.adiutors.pending'));

        $response->assertStatus(200);
    }

    public function test_admin_can_approve_adiutor_application(): void
    {
        $this->actingAs($this->admin);

        $adiutor = User::factory()->create([
            'role' => 'adiutor',
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.adiutors.approve', $adiutor->id));

        $response->assertRedirect();

        $adiutor->refresh();
        $this->assertEquals('active', $adiutor->status);
    }

    public function test_admin_can_reject_adiutor_application(): void
    {
        $this->actingAs($this->admin);

        $adiutor = User::factory()->create([
            'role' => 'adiutor',
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.adiutors.reject', $adiutor->id), [
            'reason' => 'Insufficient experience',
        ]);

        $response->assertRedirect();

        $adiutor->refresh();
        $this->assertEquals('rejected', $adiutor->status);
    }

    // =========================================
    // Bulk Actions Tests
    // =========================================

    public function test_admin_can_bulk_activate_users(): void
    {
        $this->actingAs($this->admin);

        $users = User::factory()->count(3)->create([
            'role' => 'client',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('admin.users.bulk-action'), [
            'action' => 'activate',
            'user_ids' => $users->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
    }

    public function test_admin_can_bulk_deactivate_users(): void
    {
        $this->actingAs($this->admin);

        $users = User::factory()->count(3)->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $response = $this->post(route('admin.users.bulk-action'), [
            'action' => 'deactivate',
            'user_ids' => $users->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
    }

    // =========================================
    // Export Users Tests
    // =========================================

    public function test_admin_can_export_users(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.users.export'));

        // Should return file or redirect
        $this->assertTrue(
            $response->status() === 200 || $response->isRedirection()
        );
    }
}
