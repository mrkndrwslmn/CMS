<?php

namespace App\Http\Requests\TimeTracking;

use Illuminate\Foundation\Http\FormRequest;

class StartTimerRequest extends FormRequest
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
        return [
            'task_id' => 'required|exists:tasks,taskID',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'task_id.required' => 'Please select a task to track time for.',
            'task_id.exists' => 'The selected task does not exist.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attribute names for validation messages.
     */
    public function attributes(): array
    {
        return [
            'task_id' => 'task',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from description if provided
        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->description),
            ]);
        }
    }
}
