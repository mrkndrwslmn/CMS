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
            'action' => 'required|in:add,deduct,set',
            'points' => "required|integer|min:1|max:{$maxAdjustment}",
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
            'action.required' => 'Please select an action type.',
            'action.in' => 'Invalid action type selected.',
            'points.required' => 'Please enter the number of points.',
            'points.integer' => 'Points must be a whole number.',
            'points.min' => 'Points must be at least 1.',
            'points.max' => "Points cannot exceed {$maxAdjustment}.",
            'reason.required' => 'Please provide a reason for this adjustment.',
            'reason.min' => 'Please provide a detailed reason (at least 10 characters).',
            'reason.max' => 'Reason cannot exceed 500 characters.',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (is_null($key)) {
            // Convert points based on action after validation
            $action = $validated['action'];
            $points = $validated['points'];
            $user = $this->route('user');

            if ($action === 'deduct') {
                // Make points negative for deduction
                $validated['points'] = -abs($points);
            } elseif ($action === 'set' && $user) {
                // Calculate the difference for "set to" action
                $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
                $validated['points'] = $points - $loyaltyPoint->available_points;
            }
        }

        return $validated;
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
        $action = $this->input('action');
        $points = $this->input('points');
        $user = $this->route('user');

        if ($action === 'deduct' && $user) {
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            
            if ($points > $loyaltyPoint->available_points) {
                $validator->errors()->add(
                    'points',
                    'Cannot deduct more than the available balance (' . 
                    number_format($loyaltyPoint->available_points) . ' points).'
                );
            }
        }

        // Additional validation for "set" action
        if ($action === 'set' && $user) {
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            if ($points == $loyaltyPoint->available_points) {
                $validator->errors()->add(
                    'points',
                    'No adjustment needed - the balance is already at this value.'
                );
            }
        }
    }
}
