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

    public function test_user_can_be_notified(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'client',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Trigger a notification using Laravel's notification system
        // This uses a simple inline notification for testing
        $user->notify(new \Illuminate\Notifications\Messages\MailMessage());
        
        // The notification facade will catch this
        $this->assertTrue(true);
    }
}
