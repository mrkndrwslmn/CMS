<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Notifications\PaymentConfirmedNotification;
use App\Traits\LogsNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationLoggingTest extends TestCase
{
    use LogsNotifications;
    // Using UseCmsSqlSchema from TestCase

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear any existing log records
        Log::getLogger()->getHandlers()[0]->clear();
    }

    /** @test */
    public function it_logs_successful_notification_dispatch()
    {
        // Create test data
        $user = User::factory()->create(['role' => 'client']);
        $serviceRequest = ServiceRequest::factory()->create(['client_id' => $user->id]);
        $payment = Payment::factory()->create([
            'service_request_id' => $serviceRequest->id,
            'client_id' => $user->id,
            'amount' => 1000.00
        ]);

        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Send notification
        $notification = new PaymentConfirmedNotification($payment);
        $this->sendNotificationWithLogging($user, $notification, [
            'test_context' => 'payment_test'
        ]);

        // Assert logging occurred
        $this->assertGreaterThan(0, count($logMessages));
        
        // Check for dispatch log
        $dispatchLog = collect($logMessages)->firstWhere('message', 'like', '%Dispatching PaymentConfirmedNotification%');
        $this->assertNotNull($dispatchLog);
        $this->assertEquals('info', $dispatchLog['level']);
        $this->assertArrayHasKey('notification_class', $dispatchLog['context']);
        $this->assertArrayHasKey('recipient_id', $dispatchLog['context']);
        $this->assertArrayHasKey('test_context', $dispatchLog['context']);

        // Check for success log
        $successLog = collect($logMessages)->firstWhere('message', 'like', '%dispatched successfully%');
        $this->assertNotNull($successLog);
        $this->assertEquals('info', $successLog['level']);
    }

    /** @test */
    public function it_logs_notification_dispatch_errors()
    {
        // Create a user that will cause notification to fail
        $user = new User();
        $user->id = 999999; // Non-existent ID
        $user->email = 'invalid@example.com';
        $user->role = 'client';

        // Create payment data
        $serviceRequest = ServiceRequest::factory()->create();
        $payment = Payment::factory()->create([
            'service_request_id' => $serviceRequest->id,
            'amount' => 1000.00
        ]);

        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Send notification (should fail)
        $notification = new PaymentConfirmedNotification($payment);
        $result = $this->sendNotificationWithLogging($user, $notification);

        // Assert failure was logged
        $this->assertFalse($result);
        $this->assertGreaterThan(0, count($logMessages));

        // Check for error log
        $errorLog = collect($logMessages)->firstWhere('level', 'error');
        $this->assertNotNull($errorLog);
        $this->assertStringContains('dispatch failed', $errorLog['message']);
        $this->assertArrayHasKey('error', $errorLog['context']);
        $this->assertArrayHasKey('trace', $errorLog['context']);
    }

    /** @test */
    public function it_logs_batch_notification_dispatch()
    {
        // Create multiple users
        $users = User::factory()->count(3)->create(['role' => 'admin']);
        $serviceRequest = ServiceRequest::factory()->create();
        $payment = Payment::factory()->create([
            'service_request_id' => $serviceRequest->id,
            'amount' => 1000.00
        ]);

        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Send batch notification
        $notification = new PaymentConfirmedNotification($payment);
        $result = $this->sendNotificationToManyWithLogging($users, $notification);

        // Assert batch logging occurred
        $this->assertEquals(3, $result['success']);
        $this->assertEquals(0, $result['failed']);
        $this->assertEquals(3, $result['total']);

        // Check for batch dispatch log
        $batchLog = collect($logMessages)->firstWhere('message', 'like', '%to multiple recipients%');
        $this->assertNotNull($batchLog);
        $this->assertArrayHasKey('recipient_count', $batchLog['context']);
        $this->assertEquals(3, $batchLog['context']['recipient_count']);

        // Check for batch completion log
        $completionLog = collect($logMessages)->firstWhere('message', 'like', '%batch dispatch completed%');
        $this->assertNotNull($completionLog);
        $this->assertArrayHasKey('success_rate', $completionLog['context']);
        $this->assertEquals('100%', $completionLog['context']['success_rate']);
    }

    /** @test */
    public function notification_classes_log_via_method()
    {
        $user = User::factory()->create(['role' => 'client']);
        $serviceRequest = ServiceRequest::factory()->create(['client_id' => $user->id]);
        $payment = Payment::factory()->create([
            'service_request_id' => $serviceRequest->id,
            'client_id' => $user->id
        ]);

        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Create notification instance and call via method
        $notification = new PaymentConfirmedNotification($payment);
        $channels = $notification->via($user);

        // Assert via method logging
        $viaLog = collect($logMessages)->firstWhere('message', 'PaymentConfirmedNotification dispatched');
        $this->assertNotNull($viaLog);
        $this->assertEquals('info', $viaLog['level']);
        $this->assertArrayHasKey('notification_type', $viaLog['context']);
        $this->assertArrayHasKey('payment_id', $viaLog['context']);
        $this->assertArrayHasKey('channels', $viaLog['context']);
        $this->assertEquals($channels, $viaLog['context']['channels']);
    }

    /** @test */
    public function notification_classes_log_to_array_method()
    {
        $user = User::factory()->create(['role' => 'client']);
        $serviceRequest = ServiceRequest::factory()->create(['client_id' => $user->id]);
        $payment = Payment::factory()->create([
            'service_request_id' => $serviceRequest->id,
            'client_id' => $user->id
        ]);

        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Create notification instance and call toArray method
        $notification = new PaymentConfirmedNotification($payment);
        $data = $notification->toArray($user);

        // Assert toArray method logging
        $arrayLog = collect($logMessages)->firstWhere('message', 'like', '%database notification created successfully%');
        $this->assertNotNull($arrayLog);
        $this->assertEquals('info', $arrayLog['level']);
        $this->assertArrayHasKey('notification_type', $arrayLog['context']);
        $this->assertArrayHasKey('payment_id', $arrayLog['context']);
        $this->assertArrayHasKey('data', $arrayLog['context']);
    }

    /** @test */
    public function mail_classes_log_through_base_mailable()
    {
        $serviceRequest = ServiceRequest::factory()->create();
        $payment = Payment::factory()->create([
            'service_request_id' => $serviceRequest->id
        ]);

        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Create mail instance (triggers logging in constructor)
        $mail = new \App\Mail\PaymentConfirmed($payment);

        // Assert mail creation logging
        $creationLog = collect($logMessages)->firstWhere('message', 'Mail instance created');
        $this->assertNotNull($creationLog);
        $this->assertEquals('info', $creationLog['level']);
        $this->assertArrayHasKey('mail_class', $creationLog['context']);
        $this->assertArrayHasKey('sender_type', $creationLog['context']);

        // Create envelope (triggers more logging)
        $envelope = $mail->envelope();

        // Assert envelope creation logging
        $envelopeLog = collect($logMessages)->firstWhere('message', 'Mail envelope created successfully');
        $this->assertNotNull($envelopeLog);
        $this->assertArrayHasKey('from_address', $envelopeLog['context']);
        $this->assertArrayHasKey('subject', $envelopeLog['context']);
    }

    /** @test */
    public function logs_notification_context()
    {
        // Capture log messages
        $logMessages = [];
        Log::listen(function ($level, $message, $context) use (&$logMessages) {
            $logMessages[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        });

        // Log notification context
        $this->logNotificationContext('test_event', [
            'test_data' => 'test_value',
            'user_id' => 123
        ]);

        // Assert context logging
        $contextLog = collect($logMessages)->firstWhere('message', 'Notification context: test_event');
        $this->assertNotNull($contextLog);
        $this->assertEquals('info', $contextLog['level']);
        $this->assertArrayHasKey('test_data', $contextLog['context']);
        $this->assertArrayHasKey('user_id', $contextLog['context']);
        $this->assertArrayHasKey('controller', $contextLog['context']);
        $this->assertArrayHasKey('timestamp', $contextLog['context']);
    }
}