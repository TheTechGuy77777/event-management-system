<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Mail\WaitlistNotificationMail;
use App\Models\Event;
use App\Models\Waitlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WaitlistManagerController extends Controller
{
    public function index(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $waitlists = Waitlist::where('event_id', $event->id)
            ->with('ticket')
            ->latest()
            ->paginate(20);

        return view('eventmanager.events.waitlist', compact('event', 'waitlists'));
    }

    public function notify(Event $event, Waitlist $waitlist)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // Set 30-minute priority window
        $waitlist->update([
            'is_notified' => true,
            'priority_expires_at' => now()->addMinutes(30),
        ]);

        // Send email
        Mail::to($waitlist->email)->send(
            new WaitlistNotificationMail($waitlist, $event, $waitlist->ticket)
        );

        return back()->with('success', $waitlist->name.' has been notified with a 30-minute priority window!');
    }
}
