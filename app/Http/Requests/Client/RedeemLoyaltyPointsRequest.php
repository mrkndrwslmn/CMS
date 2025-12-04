<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class RedeemLoyaltyPointsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $serviceRequest = $this->route('serviceRequest');
        
        // Verify ownership - only the client who created the request can redeem
        return $serviceRequest && $serviceRequest->client_id === $this->user()?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $minRedemption = config('loyalty.points.minimum_redemption', 100);

        return [
            'points' => "required|integer|min:{$minRedemption}",
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $minRedemption = config('loyalty.points.minimum_redemption', 100);

        return [
            'points.required' => 'Please enter the number of points to redeem.',
            'points.integer' => 'Points must be a whole number.',
            'points.min' => "Minimum redemption is {$minRedemption} points.",
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
            $this->validateServiceRequestStatus($validator);
            $this->validatePointsNotAlreadyRedeemed($validator);
            $this->validateSufficientPoints($validator);
        });
    }

    /**
     * Validate that service request is in correct status for redemption.
     */
    protected function validateServiceRequestStatus($validator): void
    {
        $serviceRequest = $this->route('serviceRequest');
        
        if ($serviceRequest && !in_array($serviceRequest->status, ['approved', 'pending_payment'])) {
            $validator->errors()->add(
                'points',
                'Points can only be redeemed for approved requests.'
            );
        }
    }

    /**
     * Validate that points haven't already been redeemed for this request.
     */
    protected function validatePointsNotAlreadyRedeemed($validator): void
    {
        $serviceRequest = $this->route('serviceRequest');
        
        if ($serviceRequest && $serviceRequest->loyalty_points_used > 0) {
            $validator->errors()->add(
                'points',
                'Loyalty points have already been redeemed for this request.'
            );
        }
    }

    /**
     * Validate that user has sufficient points available.
     */
    protected function validateSufficientPoints($validator): void
    {
        $points = $this->input('points');
        $user = $this->user();

        if ($points && $user) {
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            
            if ($points > $loyaltyPoint->available_points) {
                $validator->errors()->add(
                    'points',
                    'You do not have enough points available. Your balance is ' . 
                    number_format($loyaltyPoint->available_points) . ' points.'
                );
            }
        }
    }
}
