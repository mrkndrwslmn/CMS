<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * The reCAPTCHA site key
     */
    private string $siteKey;

    /**
     * The reCAPTCHA secret key
     */
    private string $secretKey;

    /**
     * The reCAPTCHA API URL
     */
    private string $apiUrl = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct()
    {
        $this->siteKey = config('recaptcha.site_key');
        $this->secretKey = config('recaptcha.secret_key');
    }

    /**
     * Get the reCAPTCHA site key
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * Verify the reCAPTCHA response
     */
    public function verify(string $response, ?string $remoteIp = null): bool
    {
        try {
            // Check if reCAPTCHA is enabled
            if (!config('recaptcha.enabled', true)) {
                return true; // Skip verification if disabled
            }

            // Validate required configuration
            if (empty($this->secretKey)) {
                Log::error('reCAPTCHA secret key is not configured');
                return false;
            }

            if (empty($response)) {
                Log::warning('reCAPTCHA response is empty');
                return false;
            }

            // Prepare the verification request
            $data = [
                'secret' => $this->secretKey,
                'response' => $response,
            ];

            // Add remote IP if provided
            if ($remoteIp) {
                $data['remoteip'] = $remoteIp;
            }

            // Make the API request
            $apiResponse = Http::timeout(10)
                ->asForm()
                ->post($this->apiUrl, $data);

            if (!$apiResponse->successful()) {
                Log::error('reCAPTCHA API request failed', [
                    'status' => $apiResponse->status(),
                    'body' => $apiResponse->body()
                ]);
                return false;
            }

            $result = $apiResponse->json();

            // Log the verification attempt
            Log::info('reCAPTCHA verification attempt', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'error_codes' => $result['error-codes'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'remote_ip' => $remoteIp
            ]);

            // Check for API errors
            if (isset($result['error-codes']) && !empty($result['error-codes'])) {
                Log::warning('reCAPTCHA API returned errors', [
                    'error_codes' => $result['error-codes']
                ]);
                
                // Handle specific error codes
                $errorCodes = $result['error-codes'];
                if (in_array('missing-input-secret', $errorCodes) || 
                    in_array('invalid-input-secret', $errorCodes)) {
                    Log::error('reCAPTCHA secret key configuration error');
                }
                
                return false;
            }

            $success = $result['success'] ?? false;

            // For reCAPTCHA v3, also check the score
            if ($success && isset($result['score'])) {
                $minScore = config('recaptcha.min_score', 0.5);
                $score = $result['score'];
                
                if ($score < $minScore) {
                    Log::warning('reCAPTCHA score too low', [
                        'score' => $score,
                        'min_score' => $minScore
                    ]);
                    return false;
                }
            }

            return $success;

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // In case of service failure, decide based on configuration
            // Default to false for security (fail closed)
            return config('recaptcha.fail_open', false);
        }
    }

    /**
     * Check if reCAPTCHA is enabled
     */
    public function isEnabled(): bool
    {
        return config('recaptcha.enabled', true) && !empty($this->siteKey) && !empty($this->secretKey);
    }

    /**
     * Get the reCAPTCHA version
     */
    public function getVersion(): string
    {
        return config('recaptcha.version', 'v2');
    }

    /**
     * Get the reCAPTCHA theme
     */
    public function getTheme(): string
    {
        return config('recaptcha.theme', 'light');
    }

    /**
     * Get the reCAPTCHA size
     */
    public function getSize(): string
    {
        return config('recaptcha.size', 'normal');
    }

    /**
     * Generate the reCAPTCHA HTML widget
     */
    public function getHtml(array $attributes = []): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $version = $this->getVersion();
        
        if ($version === 'v3') {
            return $this->getV3Html($attributes);
        }
        
        return $this->getV2Html($attributes);
    }

    /**
     * Generate reCAPTCHA v2 HTML
     */
    private function getV2Html(array $attributes = []): string
    {
        $defaultAttributes = [
            'class' => 'g-recaptcha',
            'data-sitekey' => $this->siteKey,
            'data-theme' => $this->getTheme(),
            'data-size' => $this->getSize(),
        ];

        $attributes = array_merge($defaultAttributes, $attributes);
        
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
        }

        return sprintf('<div%s></div>', $attributeString);
    }

    /**
     * Generate reCAPTCHA v3 HTML
     */
    private function getV3Html(array $attributes = []): string
    {
        $action = $attributes['data-action'] ?? 'form_submit';
        
        return sprintf(
            '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <script>
                grecaptcha.ready(function() {
                    grecaptcha.execute("%s", {action: "%s"}).then(function(token) {
                        document.getElementById("g-recaptcha-response").value = token;
                    });
                });
            </script>',
            $this->siteKey,
            $action
        );
    }

    /**
     * Get the reCAPTCHA script URL
     */
    public function getScriptUrl(): string
    {
        $version = $this->getVersion();
        $siteKey = $this->siteKey;
        
        if ($version === 'v3') {
            return "https://www.google.com/recaptcha/api.js?render={$siteKey}";
        }
        
        return 'https://www.google.com/recaptcha/api.js';
    }
}