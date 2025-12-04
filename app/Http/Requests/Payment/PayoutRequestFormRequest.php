<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PayoutRequestFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must be authenticated and be an adiutor
        return auth()->check() && auth()->user()->role === 'adiutor';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $minPayoutAmount = config('financial.minimum_payout_amount', 500);

        return [
            'payout_method' => 'required|in:bank_transfer,gcash,paymaya',
            'amount' => [
                'nullable',
                'numeric',
                "min:{$minPayoutAmount}",
            ],
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        $minPayoutAmount = config('financial.minimum_payout_amount', 500);

        return [
            'payout_method.required' => 'Please select a payout method.',
            'payout_method.in' => 'Invalid payout method selected. Choose bank transfer, GCash, or PayMaya.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => "Minimum payout amount is ₱{$minPayoutAmount}.",
            'notes.max' => 'Notes cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attribute names for validation messages.
     */
    public function attributes(): array
    {
        return [
            'payout_method' => 'payout method',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from notes if provided
        if ($this->has('notes')) {
            $this->merge([
                'notes' => trim($this->notes),
            ]);
        }
    }
}
