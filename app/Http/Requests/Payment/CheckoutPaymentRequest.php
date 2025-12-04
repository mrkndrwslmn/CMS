<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must be authenticated and be a client
        return auth()->check() && auth()->user()->role === 'client';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_request_id' => 'required|exists:service_requests,id',
            'payment_type' => 'required|in:full_payment,downpayment,milestone_payment,remaining_balance',
            'milestone_id' => 'nullable|required_if:payment_type,milestone_payment|exists:project_milestones,id',
            'amount' => 'nullable|numeric|min:0.01',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'service_request_id.required' => 'Service request is required.',
            'service_request_id.exists' => 'The selected service request does not exist.',
            'payment_type.required' => 'Please select a payment type.',
            'payment_type.in' => 'Invalid payment type selected.',
            'milestone_id.required_if' => 'Please select a milestone for milestone payment.',
            'milestone_id.exists' => 'The selected milestone does not exist.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be at least ₱0.01.',
        ];
    }

    /**
     * Get custom attribute names for validation messages.
     */
    public function attributes(): array
    {
        return [
            'service_request_id' => 'service request',
            'payment_type' => 'payment type',
            'milestone_id' => 'milestone',
        ];
    }
}
