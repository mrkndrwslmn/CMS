<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for storing a new client.
 * 
 * Validates all required fields for client creation including
 * name, email, phone, password, and status.
 */
class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phoneNumber' => [
                'required',
                'string',
                'max:30',
                'regex:/^[\+]?[0-9\s\-\(\)\.]{7,25}$/',
            ],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fullName.required' => 'The client name is required.',
            'fullName.max' => 'The client name cannot exceed 255 characters.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phoneNumber.required' => 'The phone number is required.',
            'phoneNumber.regex' => 'Please enter a valid phone number format.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be either active or inactive.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'fullName' => 'full name',
            'phoneNumber' => 'phone number',
        ];
    }
}
