<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WaitlistController extends Controller
{
    public function store(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $request->validate([
            'ticket_id' => ['required', 'exists:tickets,id'],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email'],
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        // Check if already on waitlist
        $existing = Waitlist::where('ticket_id', $ticket->id)
            ->where('email', $request->email)
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already on the waitlist for this ticket.');
        }

        Waitlist::create([
            'ticket_id' => $ticket->id,
            'event_id'  => $event->id,
            'name'      => $request->name,
            'email'     => $request->email,
        ]);

        return back()->with('success', 'You have been added to the waitlist! We will notify you if a ticket becomes available.');
    }
}
