<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_id' => ['required', 'exists:tickets,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_email' => ['required', 'email'],
            'buyer_phone' => ['nullable', 'string', 'max:20'],
            'gateway' => ['required', 'in:paystack'],
            'attendees' => ['nullable', 'array', 'max:10'],
            'attendees.*.name' => ['nullable', 'string', 'max:255'],
            'attendees.*.email' => ['nullable', 'email'],
            'promo_code' => ['nullable', 'string', 'max:50'],
        ];
    }
}
