<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MayaPaymentService
{
    private string $publicKey;
    private string $secretKey;
    private string $baseUrl;
    private bool $isProduction;

    public function __construct()
    {
        $this->isProduction = config('maya.environment') === 'production';
        
        if ($this->isProduction) {
            $this->publicKey = config('maya.production.public_key');
            $this->secretKey = config('maya.production.secret_key');
            $this->baseUrl = 'https://pg.paymaya.com';
        } else {
            $this->publicKey = config('maya.sandbox.public_key');
            $this->secretKey = config('maya.sandbox.secret_key');
            $this->baseUrl = 'https://pg-sandbox.paymaya.com';
        }
    }

    /**
     * Create a payment checkout
     *
     * @param array $items Array of items to be purchased
     * @param float $totalAmount The total amount to be paid
     * @param string $referenceNumber Unique reference number for the transaction
     * @param array $buyer Buyer information (firstName, lastName, contact)
     * @param array $redirectUrls URLs for success, failure, and cancel
     * @return array Response from the API
     */
    public function createCheckout(
        array $items,
        float $totalAmount,
        string $referenceNumber,
        array $buyer,
        array $redirectUrls
    ): array {
        try {
            $url = $this->baseUrl . '/checkout/v1/checkouts';

            $payload = [
                'totalAmount' => [
                    'value' => $totalAmount,
                    'currency' => 'PHP'
                ],
                'items' => $items,
                'requestReferenceNumber' => $referenceNumber,
                'redirectUrl' => [
                    'success' => $redirectUrls['success'],
                    'failure' => $redirectUrls['failure'],
                    'cancel' => $redirectUrls['cancel']
                ],
                'buyer' => $buyer
            ];

            Log::info('Maya Payment: Creating checkout', [
                'reference' => $referenceNumber,
                'amount' => $totalAmount
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->publicKey . ':')
            ])->post($url, $payload);

            if ($response->failed()) {
                Log::error('Maya Payment: Checkout creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'error' => true,
                    'message' => 'Failed to create checkout: ' . $response->body()
                ];
            }

            $data = $response->json();

            Log::info('Maya Payment: Checkout created successfully', [
                'checkoutId' => $data['checkoutId'] ?? null,
                'reference' => $referenceNumber
            ]);

            return [
                'error' => false,
                'data' => [
                    'checkoutId' => $data['checkoutId'] ?? null,
                    'redirectUrl' => $data['redirectUrl'] ?? null
                ]
            ];
        } catch (Exception $e) {
            Log::error('Maya Payment: Exception during checkout creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => true,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify the status of a payment
     *
     * @param string $checkoutId The checkout ID returned from createCheckout
     * @return array Response from the API
     */
    public function verifyPayment(string $checkoutId): array
    {
        try {
            $url = $this->baseUrl . '/checkout/v1/checkouts/' . $checkoutId;

            Log::info('Maya Payment: Verifying payment', ['checkoutId' => $checkoutId]);

            // Use secret key for payment verification
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
            ])->get($url);

            if ($response->failed()) {
                Log::error('Maya Payment: Verification failed', [
                    'checkoutId' => $checkoutId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'error' => true,
                    'message' => 'Failed to verify payment: ' . $response->body()
                ];
            }

            $data = $response->json();

            Log::info('Maya Payment: Payment verified', [
                'checkoutId' => $checkoutId,
                'status' => $data['status'] ?? 'unknown'
            ]);

            return [
                'error' => false,
                'data' => $data
            ];
        } catch (Exception $e) {
            Log::error('Maya Payment: Exception during verification', [
                'checkoutId' => $checkoutId,
                'message' => $e->getMessage()
            ]);

            return [
                'error' => true,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if service is in production mode
     */
    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * Get current environment name
     */
    public function getEnvironment(): string
    {
        return $this->isProduction ? 'production' : 'sandbox';
    }
}
