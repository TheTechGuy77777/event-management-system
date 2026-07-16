<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrderItem;
use App\Policies\EventPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckInController extends Controller
{
    public function index(Event $event)
    {
        $this->authorize(EventPolicy::class.'.manageAttendees', $event);

        $stats = Cache::remember("checkin_stats_{$event->id}", 60, function () use ($event) {
            return [
                'totalAttendees' => OrderItem::completedForEvent($event->id)->count(),
                'totalCheckedIn' => OrderItem::completedForEvent($event->id)->checkedIn()->count(),
            ];
        });

        $recentCheckIns = OrderItem::completedForEvent($event->id)
            ->checkedIn()
            ->with('ticket')
            ->latest('checked_in_at')
            ->take(10)
            ->get();

        return view('eventmanager.events.checkin', compact(
            'event',
            'stats',
            'recentCheckIns'
        ));
    }

    public function scan(Request $request, Event $event)
    {
        $this->authorize(EventPolicy::class.'.manageAttendees', $event);

        $request->validate([
            'ticket_code' => ['required', 'string'],
        ]);

        $code = strtoupper(trim($request->ticket_code));

        $orderItem = OrderItem::where('ticket_code', $code)
            ->completedForEvent($event->id)
            ->with(['ticket', 'order'])
            ->first();

        if (! $orderItem) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'This ticket code is not valid for this event.',
            ]);
        }

        if ($orderItem->is_checked_in) {
            return response()->json([
                'status' => 'already_used',
                'message' => 'This ticket has already been checked in.',
                'attendee_name' => $orderItem->attendee_name,
                'ticket_type' => $orderItem->ticket->name ?? '—',
                'checked_in_at' => $orderItem->checked_in_at?->format('h:i A'),
            ]);
        }

        $orderItem->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in successful!',
            'attendee_name' => $orderItem->attendee_name,
            'ticket_type' => $orderItem->ticket->name ?? '—',
            'ticket_code' => $orderItem->ticket_code,
        ]);
    }
}
