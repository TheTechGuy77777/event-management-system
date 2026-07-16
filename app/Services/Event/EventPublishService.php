<?php

namespace App\Services\Event;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventPublishService
{
    public function publish(Event $event): Event
    {
        if ($event->tickets->isEmpty()) {
            throw new \RuntimeException('You must add at least one ticket before publishing.');
        }

        $qrPath = 'qrcodes/event-'.$event->id.'.svg';
        $fullPath = storage_path('app/public/'.$qrPath);

        Storage::disk('public')->makeDirectory('qrcodes');

        QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate(url('/events/'.$event->slug), $fullPath);

        $event->update([
            'status' => 'published',
            'published_at' => now(),
            'qr_code' => $qrPath,
        ]);

        return $event->fresh();
    }
}
