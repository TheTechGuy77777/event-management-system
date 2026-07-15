<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
            'event_id' => ['required', 'exists:events,id'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
