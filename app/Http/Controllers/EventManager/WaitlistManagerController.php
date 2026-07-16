<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Mail\WaitlistNotificationMail;
use App\Models\Event;
use App\Models\Waitlist;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Mail;

class WaitlistManagerController extends Controller
{
    public function index(Event $event)
    {
        $this->authorize(EventPolicy::class.'.manageAttendees', $event);

        $waitlists = Waitlist::where('event_id', $event->id)
            ->with('ticket')
            ->latest()
            ->paginate(20);

        return view('eventmanager.events.waitlist', compact('event', 'waitlists'));
    }

    public function notify(Event $event, Waitlist $waitlist)
    {
        $this->authorize(EventPolicy::class.'.manageAttendees', $event);

        $waitlist->update([
            'is_notified' => true,
            'priority_expires_at' => now()->addMinutes(30),
        ]);

        Mail::to($waitlist->email)->send(
            new WaitlistNotificationMail($waitlist, $event, $waitlist->ticket)
        );

        return back()->with('success', $waitlist->name.' has been notified with a 30-minute priority window!');
    }
}
