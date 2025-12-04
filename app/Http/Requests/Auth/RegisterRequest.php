<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Form request for user registration validation.
 * 
 * Handles validation rules, custom messages, and password strength requirements.
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Guest middleware handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $passwordRules = Password::min(8)
            ->mixedCase()      // Require uppercase and lowercase
            ->numbers()        // Require at least one number
            ->symbols();       // Require at least one special character

        // Skip breach check in testing environment to avoid external API calls
        if (!app()->environment('testing')) {
            $passwordRules->uncompromised();
        }

        return [
            'fullName' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.\']+$/u'],
            'email' => ['required', 'string', 'email:rfc', 'max:100', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                $passwordRules,
            ],
            'phoneNumber' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'referralCode' => ['nullable', 'string', 'max:20', 'alpha_num'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fullName.required' => 'Please enter your full name.',
            'fullName.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email already exists.',
            'password.required' => 'Please create a password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phoneNumber.regex' => 'Please enter a valid phone number.',
            'referralCode.alpha_num' => 'Invalid referral code format.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'fullName' => 'full name',
            'phoneNumber' => 'phone number',
            'referralCode' => 'referral code',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from string inputs
        $this->merge([
            'fullName' => $this->fullName ? trim($this->fullName) : null,
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'phoneNumber' => $this->phoneNumber ? preg_replace('/\s+/', '', $this->phoneNumber) : null,
            'referralCode' => $this->referralCode ? strtoupper(trim($this->referralCode)) : null,
        ]);
    }
}
