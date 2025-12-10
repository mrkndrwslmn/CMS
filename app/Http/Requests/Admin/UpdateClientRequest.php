<?php

namespace App\Http\Requests\Admin;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request for updating an existing client.
 * 
 * Validates client profile fields including name, email, phone, and status.
 * Email uniqueness check excludes the current client being updated.
 */
class UpdateClientRequest extends FormRequest
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
        // Get the client ID from the route parameter
        $clientId = $this->route('client');

        return [
            'fullName' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($clientId),
            ],
            'phoneNumber' => [
                'required',
                'string',
                'max:25',
                new PhoneNumber(),
            ],
            'status' => ['required', 'in:active,inactive,banned'],
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
            'email.unique' => 'This email address is already registered to another user.',
            'phoneNumber.required' => 'The phone number is required.',
            'phoneNumber.regex' => 'Please enter a valid phone number format.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be active, inactive, or banned.',
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
