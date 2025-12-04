<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization is handled in the controller (project ownership check).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'quality_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'timeliness_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'would_recommend' => 'boolean',
            'public' => 'boolean',
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
            'rating.required' => 'Please provide an overall rating.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'quality_rating.required' => 'Please rate the quality of work.',
            'communication_rating.required' => 'Please rate the communication.',
            'timeliness_rating.required' => 'Please rate the timeliness.',
            'comment.required' => 'Please provide your feedback comments.',
            'comment.min' => 'Your feedback must be at least 10 characters.',
            'comment.max' => 'Your feedback cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'rating' => 'overall rating',
            'quality_rating' => 'quality rating',
            'communication_rating' => 'communication rating',
            'timeliness_rating' => 'timeliness rating',
            'comment' => 'feedback comment',
        ];
    }

    /**
     * Calculate the average rating from all rating fields.
     *
     * @return int
     */
    public function averageRating(): int
    {
        return (int) round((
            $this->rating +
            $this->quality_rating +
            $this->communication_rating +
            $this->timeliness_rating
        ) / 4);
    }
}
