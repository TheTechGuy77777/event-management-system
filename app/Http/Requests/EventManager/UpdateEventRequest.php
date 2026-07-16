<?php

namespace App\Http\Requests\EventManager;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:75'],
            'description' => ['required', 'string'],
            'event_type' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'payment_model' => ['required', 'in:attendee_pays,manager_pays'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
        ];
    }
}
