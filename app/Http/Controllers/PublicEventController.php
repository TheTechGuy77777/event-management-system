<?php

namespace App\Http\Controllers;

use App\Models\Event;

class PublicEventController extends Controller
{
    public function show($slug)
    {
        $event = Event::with([
            'category',
            'tickets.perks',
            'lineup',
            'user',
            'orders'
        ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('public.event', compact('event'));
    }
}
