<?php

namespace App\Http\Requests\EventManager;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'timezone' => ['nullable', 'string'],
            'payment_model' => ['required', 'in:attendee_pays,manager_pays'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'action' => ['nullable', 'string'],

            'event_mode' => ['required', 'in:physical,online,hybrid'],

            // Physical / Hybrid
            'country' => ['required_if:event_mode,physical,hybrid', 'nullable', 'string'],
            'location' => ['required_if:event_mode,physical,hybrid', 'nullable', 'string', 'max:255'],

            // Online / Hybrid
            'platform' => ['required_if:event_mode,online,hybrid', 'nullable', 'in:zoom,zoom_webinar,google_meet,microsoft_teams,youtube_live,custom'],
            'meeting_link' => ['required_if:event_mode,online,hybrid', 'nullable', 'url'],
            'meeting_id' => ['nullable', 'string', 'max:100'],
            'meeting_passcode' => ['nullable', 'string', 'max:50'],
            'whatsapp_link' => ['required_if:event_mode,online,hybrid', 'nullable', 'url'],

            // Recurring
            'is_recurring' => ['nullable', 'in:0,1'],
            'recurrence_rule' => ['nullable', 'in:daily,weekly,monthly'],
            'recurrence_end' => ['nullable', 'date'],

            // Social links
            'instagram' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url'],

            // Lineup + tickets are handled separately in the controller,
            // not passed through validated() into Event::create()
            'lineup' => ['nullable', 'array'],
            'lineup.*.name' => ['nullable', 'string', 'max:255'],
            'lineup.*.role' => ['nullable', 'string', 'max:255'],
            'tickets' => ['nullable', 'array'],
        ];
    }
}
