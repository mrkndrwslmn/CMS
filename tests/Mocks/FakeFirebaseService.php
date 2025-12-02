<?php

namespace Tests\Mocks;

use App\Models\User;

/**
 * Fake Firebase Service for Testing
 * 
 * This mock service captures all notifications that would be sent
 * and provides assertion methods for testing.
 */
class FakeFirebaseService
{
    /**
     * Array of all sent notifications
     */
    public array $sentNotifications = [];

    /**
     * Array of all sent topic notifications
     */
    public array $topicNotifications = [];

    /**
     * Whether the service should simulate success or failure
     */
    protected bool $shouldSucceed = true;

    /**
     * Send push notification to a specific user
     */
    public function sendToUser(User $user, array $data = [], array $notification = []): bool
    {
        $this->sentNotifications[] = [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'fcm_token' => $user->fcm_token,
            'data' => $data,
            'notification' => $notification,
            'sent_at' => now()->toDateTimeString(),
        ];

        return $this->shouldSucceed;
    }

    /**
     * Send push notification to a topic
     */
    public function sendToTopic(string $topic, array $data = [], array $notification = []): bool
    {
        $this->topicNotifications[] = [
            'topic' => $topic,
            'data' => $data,
            'notification' => $notification,
            'sent_at' => now()->toDateTimeString(),
        ];

        return $this->shouldSucceed;
    }

    /**
     * Subscribe user to a topic
     */
    public function subscribeToTopic(User $user, string $topic): bool
    {
        return $this->shouldSucceed;
    }

    /**
     * Unsubscribe user from a topic
     */
    public function unsubscribeFromTopic(User $user, string $topic): bool
    {
        return $this->shouldSucceed;
    }

    // =========================================
    // Test Helper Methods
    // =========================================

    /**
     * Set the service to fail all operations
     */
    public function shouldFail(): self
    {
        $this->shouldSucceed = false;
        return $this;
    }

    /**
     * Set the service to succeed all operations
     */
    public function shouldSucceed(): self
    {
        $this->shouldSucceed = true;
        return $this;
    }

    /**
     * Reset all sent notifications
     */
    public function reset(): self
    {
        $this->sentNotifications = [];
        $this->topicNotifications = [];
        $this->shouldSucceed = true;
        return $this;
    }

    /**
     * Assert that a notification was sent to a specific user
     */
    public function assertSentTo(User $user): bool
    {
        foreach ($this->sentNotifications as $notification) {
            if ($notification['user_id'] === $user->id) {
                return true;
            }
        }

        throw new \PHPUnit\Framework\ExpectationFailedException(
            "No notification was sent to user ID: {$user->id}"
        );
    }

    /**
     * Assert that no notification was sent to a specific user
     */
    public function assertNotSentTo(User $user): bool
    {
        foreach ($this->sentNotifications as $notification) {
            if ($notification['user_id'] === $user->id) {
                throw new \PHPUnit\Framework\ExpectationFailedException(
                    "A notification was unexpectedly sent to user ID: {$user->id}"
                );
            }
        }

        return true;
    }

    /**
     * Assert that no notifications were sent at all
     */
    public function assertNothingSent(): bool
    {
        if (!empty($this->sentNotifications) || !empty($this->topicNotifications)) {
            $count = count($this->sentNotifications) + count($this->topicNotifications);
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected no notifications to be sent, but {$count} were sent."
            );
        }

        return true;
    }

    /**
     * Assert that a specific number of notifications were sent
     */
    public function assertSentCount(int $expected): bool
    {
        $actual = count($this->sentNotifications);

        if ($actual !== $expected) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected {$expected} notifications to be sent, but {$actual} were sent."
            );
        }

        return true;
    }

    /**
     * Assert that a notification with specific data was sent
     */
    public function assertSentWithData(array $expectedData): bool
    {
        foreach ($this->sentNotifications as $notification) {
            if ($this->arrayContainsAll($notification['data'], $expectedData)) {
                return true;
            }
        }

        throw new \PHPUnit\Framework\ExpectationFailedException(
            "No notification was sent with the expected data."
        );
    }

    /**
     * Assert a notification was sent to a topic
     */
    public function assertSentToTopic(string $topic): bool
    {
        foreach ($this->topicNotifications as $notification) {
            if ($notification['topic'] === $topic) {
                return true;
            }
        }

        throw new \PHPUnit\Framework\ExpectationFailedException(
            "No notification was sent to topic: {$topic}"
        );
    }

    /**
     * Get all notifications sent to a specific user
     */
    public function getNotificationsFor(User $user): array
    {
        return array_filter($this->sentNotifications, function ($notification) use ($user) {
            return $notification['user_id'] === $user->id;
        });
    }

    /**
     * Get the last sent notification
     */
    public function getLastNotification(): ?array
    {
        if (empty($this->sentNotifications)) {
            return null;
        }

        return end($this->sentNotifications);
    }

    /**
     * Check if array contains all expected keys/values
     */
    protected function arrayContainsAll(array $haystack, array $needles): bool
    {
        foreach ($needles as $key => $value) {
            if (!isset($haystack[$key]) || $haystack[$key] !== $value) {
                return false;
            }
        }

        return true;
    }
}
