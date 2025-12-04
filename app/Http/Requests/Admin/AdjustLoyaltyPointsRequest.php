<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdjustLoyaltyPointsRequest extends FormRequest
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
        $maxAdjustment = config('loyalty.admin.max_adjustment', 100000);

        return [
            'points' => "required|integer|not_in:0|between:-{$maxAdjustment},{$maxAdjustment}",
            'reason' => 'required|string|min:10|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $maxAdjustment = config('loyalty.admin.max_adjustment', 100000);

        return [
            'points.required' => 'Please enter the number of points to adjust.',
            'points.integer' => 'Points must be a whole number.',
            'points.not_in' => 'Points adjustment cannot be zero.',
            'points.between' => "Points adjustment must be between -{$maxAdjustment} and {$maxAdjustment}.",
            'reason.required' => 'Please provide a reason for this adjustment.',
            'reason.min' => 'Please provide a detailed reason (at least 10 characters).',
            'reason.max' => 'Reason cannot exceed 500 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateSufficientBalance($validator);
        });
    }

    /**
     * Validate that deductions don't exceed available balance.
     */
    protected function validateSufficientBalance($validator): void
    {
        $points = $this->input('points');
        $user = $this->route('user');

        if ($points < 0 && $user) {
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            
            if (abs($points) > $loyaltyPoint->available_points) {
                $validator->errors()->add(
                    'points',
                    'Cannot deduct more than the available balance (' . 
                    number_format($loyaltyPoint->available_points) . ' points).'
                );
            }
        }
    }
}
