<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Request;

class RecaptchaValidation implements ValidationRule
{
    private RecaptchaService $recaptchaService;

    public function __construct()
    {
        $this->recaptchaService = app(RecaptchaService::class);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if reCAPTCHA is disabled
        if (!$this->recaptchaService->isEnabled()) {
            return;
        }

        // Check if user's IP should skip reCAPTCHA
        $userIp = Request::ip();
        $skipIps = config('recaptcha.skip_ips', []);
        
        if (in_array($userIp, $skipIps)) {
            return;
        }

        // Validate the reCAPTCHA response
        if (!$this->recaptchaService->verify($value, $userIp)) {
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
