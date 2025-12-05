<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Form request for user login validation.
 * 
 * Handles validation rules and custom error messages for the login form.
 */
class LoginRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        \Log::error('Login Validation Failed', [
            'errors' => $validator->errors()->toArray(),
            'inputs' => $this->except(['password'])
        ]);

        throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Guest middleware handles authorization
    }

    /**
     * Prepare the data for validation.
     * 
     * Converts checkbox 'on' value to boolean for remember field.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('remember')) {
            $this->merge([
                'remember' => filter_var($this->remember, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? ($this->remember === 'on'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
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
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ];
    }

    /**
     * Get credentials for authentication attempt.
     *
     * @return array<string, string>
     */
    public function credentials(): array
    {
        return $this->only('email', 'password');
    }

    /**
     * Check if "remember me" is enabled.
     */
    public function rememberMe(): bool
    {
        return $this->boolean('remember');
    }
}
