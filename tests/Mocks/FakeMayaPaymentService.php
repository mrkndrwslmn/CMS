<?php

namespace Tests\Mocks;

use App\Services\MayaPaymentService;

/**
 * Fake Maya Payment Service for Testing
 * 
 * This mock service simulates Maya payment gateway operations
 * without making actual API calls.
 */
class FakeMayaPaymentService extends MayaPaymentService
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

    public function __construct()
    {
        // Don't call parent constructor - we don't need real Maya API credentials for testing
    }

    /**
     * Create a checkout session
     * Override parent signature to match
     */
    public function createCheckout(
        array $items,
        float $totalAmount,
        string $referenceNumber,
        array $buyer,
        array $redirectUrls
    ): array {
        $checkoutId = 'mock_checkout_' . uniqid();

        $this->createdCheckouts[] = [
            'checkout_id' => $checkoutId,
            'items' => $items,
            'total_amount' => $totalAmount,
            'reference_number' => $referenceNumber,
            'buyer' => $buyer,
            'redirect_urls' => $redirectUrls,
            'created_at' => now()->toDateTimeString(),
        ];

        if ($this->shouldSucceed) {
            return [
                'error' => false,
                'data' => [
                    'checkoutId' => $checkoutId,
                    'redirectUrl' => 'https://sandbox.maya.ph/checkout/' . $checkoutId,
                ]
            ];
        }

        return [
            'error' => true,
            'message' => $this->failureMessage,
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
                'error' => false,
                'data' => [
                    'status' => 'PAYMENT_SUCCESS',
                    'paymentStatus' => 'completed',
                    'amount' => 1000.00,
                    'currency' => 'PHP',
                    'requestReferenceNumber' => 'mock_ref_' . uniqid(),
                ]
            ];
        }

        return [
            'error' => true,
            'message' => $this->failureMessage,
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
                'error' => false,
                'data' => [
                    'refundId' => $refundId,
                    'status' => 'REFUND_SUCCESS',
                    'amount' => $amount,
                ]
            ];
        }

        return [
            'error' => true,
            'message' => $this->failureMessage,
        ];
    }

    /**
     * Get checkout details
     */
    public function getCheckout(string $checkoutId): array
    {
        if ($this->shouldSucceed) {
            return [
                'error' => false,
                'data' => [
                    'checkoutId' => $checkoutId,
                    'status' => 'COMPLETED',
                    'amount' => 1000.00,
                    'currency' => 'PHP',
                ]
            ];
        }

        return [
            'error' => true,
            'message' => 'Checkout not found',
        ];
    }

    /**
     * Check if service is in production mode
     */
    public function isProduction(): bool
    {
        return false;
    }

    /**
     * Get current environment name
     */
    public function getEnvironment(): string
    {
        return 'sandbox';
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
            if ($this->arrayContainsAll($checkout, $expectedData)) {
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

