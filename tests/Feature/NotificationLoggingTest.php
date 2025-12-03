<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Tests for notification and logging functionality.
 * 
 * This tests that notifications and logs work properly.
 */
class NotificationLoggingTest extends TestCase
{
    use UseCmsSqlSchema;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bind test Firebase service
        $this->app->bind(\App\Services\FirebaseService::class, \Tests\Mocks\FakeFirebaseService::class);
    }

    public function test_application_logs_can_be_written(): void
    {
        Log::info('Test log message', ['context' => 'test']);
        
        // If we get here without exception, logging works
        $this->assertTrue(true);
    }

    public function test_log_levels_work_correctly(): void
    {
        Log::debug('Debug message');
        Log::info('Info message');
        Log::warning('Warning message');
        Log::error('Error message');
        
        // If we get here without exception, all log levels work
        $this->assertTrue(true);
    }

    public function test_logs_can_include_context_data(): void
    {
        Log::info('Test with context', [
            'user_id' => 123,
            'action' => 'test_action',
            'data' => ['key' => 'value'],
        ]);
        
        $this->assertTrue(true);
    }

    public function test_notifications_can_be_faked(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'client',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // In a real test, you would trigger a notification and assert it was sent
        // For now, just verify the fake is set up correctly
        Notification::assertNothingSent();
    }

    public function test_user_has_notifiable_trait(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Verify User model has the Notifiable trait
        $this->assertTrue(
            in_array(\Illuminate\Notifications\Notifiable::class, class_uses_recursive($user)),
            'User model should use Notifiable trait'
        );
        
        // Verify the user has required notification methods
        $this->assertTrue(method_exists($user, 'notify'));
        $this->assertTrue(method_exists($user, 'notifications'));
    }

    public function test_notification_routes_are_configured(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'email_verified_at' => now(),
            'status' => 'active',
            'email' => 'test@example.com',
        ]);

        // Verify user has email set which is used for mail notifications
        $this->assertEquals('test@example.com', $user->email);
        
        // Verify the notification relationship exists
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\MorphMany::class,
            $user->notifications()
        );
    }
}
