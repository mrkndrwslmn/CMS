<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for storing and updating client notes.
 * 
 * Validates note title, content, and type.
 * Used for both creating new notes and updating existing ones.
 */
class StoreNoteRequest extends FormRequest
{
    /**
     * Valid note types for CRM-like tracking.
     */
    public const NOTE_TYPES = ['general', 'important', 'reminder', 'issue'];

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
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:10000'],
            'type' => ['required', 'in:' . implode(',', self::NOTE_TYPES)],
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
            'title.required' => 'The note title is required.',
            'title.max' => 'The note title cannot exceed 255 characters.',
            'content.required' => 'The note content is required.',
            'content.max' => 'The note content cannot exceed 10,000 characters.',
            'type.required' => 'Please select a note type.',
            'type.in' => 'The note type must be one of: ' . implode(', ', self::NOTE_TYPES) . '.',
        ];
    }
}
