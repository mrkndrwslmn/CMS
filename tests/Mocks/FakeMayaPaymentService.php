<?php

namespace Tests\Mocks;

/**
 * Fake Maya Payment Service for Testing
 * 
 * This mock service simulates Maya payment gateway operations
 * without making actual API calls.
 */
class FakeMayaPaymentService
{
    /**
     * Array of all created checkouts
     */
    public array $createdCheckouts = [];

    /**
     * Array of all verified payments
     */
    public array $verifiedPayments = [];

    /**
     * Array of all refunded payments
     */
    public array $refundedPayments = [];

    /**
     * Whether the service should simulate success or failure
     */
    protected bool $shouldSucceed = true;

    /**
     * Custom failure message
     */
    protected string $failureMessage = 'Mock payment failed';

    /**
     * Custom failure code
     */
    protected string $failureCode = 'MOCK_FAILURE';

    /**
     * Create a checkout session
     */
    public function createCheckout(array $data): array
    {
        $checkoutId = 'mock_checkout_' . uniqid();

        $this->createdCheckouts[] = [
            'checkout_id' => $checkoutId,
            'data' => $data,
            'created_at' => now()->toDateTimeString(),
        ];

        if ($this->shouldSucceed) {
            return [
                'success' => true,
                'checkout_id' => $checkoutId,
                'checkout_url' => 'https://sandbox.maya.ph/checkout/' . $checkoutId,
                'redirect_url' => 'https://sandbox.maya.ph/checkout/' . $checkoutId,
                'expires_at' => now()->addHours(24)->toIso8601String(),
            ];
        }

        return [
            'success' => false,
            'error' => $this->failureMessage,
            'error_code' => $this->failureCode,
        ];
    }

    /**
     * Verify a payment by checkout ID
     */
    public function verifyPayment(string $checkoutId): array
    {
        $this->verifiedPayments[] = [
            'checkout_id' => $checkoutId,
            'verified_at' => now()->toDateTimeString(),
        ];

        if ($this->shouldSucceed) {
            return [
                'success' => true,
                'status' => 'PAYMENT_SUCCESS',
                'payment_status' => 'completed',
                'amount' => 1000.00,
                'currency' => 'PHP',
                'reference' => 'mock_ref_' . uniqid(),
                'payment_method' => 'card',
                'paid_at' => now()->toIso8601String(),
            ];
        }

        return [
            'success' => false,
            'status' => 'PAYMENT_FAILED',
            'payment_status' => 'failed',
            'error' => $this->failureMessage,
            'error_code' => $this->failureCode,
        ];
    }

    /**
     * Process a refund
     */
    public function refund(string $paymentId, float $amount, string $reason = ''): array
    {
        $refundId = 'mock_refund_' . uniqid();

        $this->refundedPayments[] = [
            'payment_id' => $paymentId,
            'refund_id' => $refundId,
            'amount' => $amount,
            'reason' => $reason,
            'created_at' => now()->toDateTimeString(),
        ];

        if ($this->shouldSucceed) {
            return [
                'success' => true,
                'refund_id' => $refundId,
                'status' => 'REFUND_SUCCESS',
                'amount' => $amount,
            ];
        }

        return [
            'success' => false,
            'error' => $this->failureMessage,
            'error_code' => $this->failureCode,
        ];
    }

    /**
     * Get checkout details
     */
    public function getCheckout(string $checkoutId): array
    {
        if ($this->shouldSucceed) {
            return [
                'success' => true,
                'checkout_id' => $checkoutId,
                'status' => 'COMPLETED',
                'amount' => 1000.00,
                'currency' => 'PHP',
            ];
        }

        return [
            'success' => false,
            'error' => 'Checkout not found',
            'error_code' => 'CHECKOUT_NOT_FOUND',
        ];
    }

    // =========================================
    // Test Helper Methods
    // =========================================

    /**
     * Set the service to fail all operations
     */
    public function shouldFail(string $message = 'Mock payment failed', string $code = 'MOCK_FAILURE'): self
    {
        $this->shouldSucceed = false;
        $this->failureMessage = $message;
        $this->failureCode = $code;
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
     * Reset all data
     */
    public function reset(): self
    {
        $this->createdCheckouts = [];
        $this->verifiedPayments = [];
        $this->refundedPayments = [];
        $this->shouldSucceed = true;
        $this->failureMessage = 'Mock payment failed';
        $this->failureCode = 'MOCK_FAILURE';
        return $this;
    }

    /**
     * Assert that a checkout was created
     */
    public function assertCheckoutCreated(): bool
    {
        if (empty($this->createdCheckouts)) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "No checkout was created."
            );
        }

        return true;
    }

    /**
     * Assert that a specific number of checkouts were created
     */
    public function assertCheckoutCount(int $expected): bool
    {
        $actual = count($this->createdCheckouts);

        if ($actual !== $expected) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected {$expected} checkouts to be created, but {$actual} were created."
            );
        }

        return true;
    }

    /**
     * Assert that a checkout was created with specific data
     */
    public function assertCheckoutCreatedWith(array $expectedData): bool
    {
        foreach ($this->createdCheckouts as $checkout) {
            if ($this->arrayContainsAll($checkout['data'], $expectedData)) {
                return true;
            }
        }

        throw new \PHPUnit\Framework\ExpectationFailedException(
            "No checkout was created with the expected data."
        );
    }

    /**
     * Assert that a payment was verified
     */
    public function assertPaymentVerified(string $checkoutId = null): bool
    {
        if (empty($this->verifiedPayments)) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "No payment was verified."
            );
        }

        if ($checkoutId !== null) {
            foreach ($this->verifiedPayments as $payment) {
                if ($payment['checkout_id'] === $checkoutId) {
                    return true;
                }
            }

            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Payment with checkout ID {$checkoutId} was not verified."
            );
        }

        return true;
    }

    /**
     * Assert that a refund was processed
     */
    public function assertRefundProcessed(string $paymentId = null): bool
    {
        if (empty($this->refundedPayments)) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "No refund was processed."
            );
        }

        if ($paymentId !== null) {
            foreach ($this->refundedPayments as $refund) {
                if ($refund['payment_id'] === $paymentId) {
                    return true;
                }
            }

            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Refund for payment ID {$paymentId} was not processed."
            );
        }

        return true;
    }

    /**
     * Assert nothing was done
     */
    public function assertNothingDone(): bool
    {
        $total = count($this->createdCheckouts) + count($this->verifiedPayments) + count($this->refundedPayments);

        if ($total > 0) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected no operations, but {$total} were performed."
            );
        }

        return true;
    }

    /**
     * Get the last created checkout
     */
    public function getLastCheckout(): ?array
    {
        if (empty($this->createdCheckouts)) {
            return null;
        }

        return end($this->createdCheckouts);
    }

    /**
     * Get the last verified payment
     */
    public function getLastVerification(): ?array
    {
        if (empty($this->verifiedPayments)) {
            return null;
        }

        return end($this->verifiedPayments);
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
