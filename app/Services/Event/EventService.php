<?php

namespace App\Services\Event;

use App\Models\Event;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventService
{
    public function generateUniqueSlug(string $baseSlug, ?int $excludeId = null): string
    {
        $slug = Str::slug($baseSlug);
        $originalSlug = $slug;
        $count = 1;

        $query = Event::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug.'-'.$count++;
            $query = Event::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    public function handleCoverImage(?UploadedFile $file, ?string $existingPath = null): ?string
    {
        if ($file) {
            if ($existingPath) {
                Storage::disk('public')->delete($existingPath);
            }

            return $file->store('events', 'public');
        }

        return $existingPath;
    }

    public function createEvent(array $data, ?UploadedFile $coverImage = null, array $tickets = [], array $lineup = []): Event
    {
        $slug = $this->generateUniqueSlug($data['name'] ?? '');
        $coverPath = $this->handleCoverImage($coverImage);

        $event = Event::create(array_merge($data, [
            'user_id' => Auth::id(),
            'slug' => $slug,
            'cover_image' => $coverPath,
            'commission_rate' => 0,
            'status' => 'draft',
        ]));

        $this->createTickets($event, $tickets);
        $this->createLineup($event, $lineup);

        return $event;
    }

    public function updateEvent(Event $event, array $data, ?UploadedFile $coverImage = null, array $tickets = [], array $lineup = []): Event
    {
        $slug = $this->generateUniqueSlug($data['name'] ?? $event->name, $event->id);
        $coverPath = $this->handleCoverImage($coverImage, $event->cover_image);

        $event->update(array_merge($data, [
            'slug' => $slug,
            'cover_image' => $coverPath,
        ]));

        return $event->fresh();
    }

    private function createTickets(Event $event, array $tickets): void
    {
        foreach ($tickets as $ticketData) {
            if (empty($ticketData['name'])) {
                continue;
            }

            $event->tickets()->create([
                'name' => $ticketData['name'],
                'ticket_type' => $ticketData['ticket_type'] ?? 'paid',
                'admission_type' => $ticketData['admission_type'] ?? 'single',
                'group_size' => $ticketData['group_size'] ?? null,
                'price' => $ticketData['price'] ?? 0,
                'quantity' => $ticketData['quantity'] ?? 0,
                'purchase_limit' => $ticketData['purchase_limit'] ?? 1,
                'description' => $ticketData['description'] ?? null,
                'is_active' => true,
            ]);
        }
    }

    private function createLineup(Event $event, array $lineup): void
    {
        foreach ($lineup as $member) {
            if (empty($member['name'])) {
                continue;
            }

            $event->lineup()->create([
                'name' => $member['name'],
                'role' => $member['role'] ?? '',
            ]);
        }
    }
}
