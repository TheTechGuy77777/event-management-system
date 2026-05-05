<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function index(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $totalAttendees = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })->count();

        $totalCheckedIn = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })->where('is_checked_in', true)->count();

        $recentCheckIns = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })
            ->where('is_checked_in', true)
            ->with('ticket')
            ->latest('checked_in_at')
            ->take(10)
            ->get();

        return view('eventmanager.events.checkin', compact(
            'event',
            'totalAttendees',
            'totalCheckedIn',
            'recentCheckIns'
        ));
    }

    public function scan(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'ticket_code' => ['required', 'string'],
        ]);

        $code = strtoupper(trim($request->ticket_code));

        // Find the ticket
        $orderItem = OrderItem::where('ticket_code', $code)
            ->whereHas('order', function ($q) use ($event) {
                $q->where('event_id', $event->id)
                    ->where('payment_status', 'completed');
            })
            ->with(['ticket', 'order'])
            ->first();

        // Invalid ticket
        if (!$orderItem) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'This ticket code is not valid for this event.',
            ]);
        }

        // Already checked in
        if ($orderItem->is_checked_in) {
            return response()->json([
                'status'        => 'already_used',
                'message'       => 'This ticket has already been checked in.',
                'attendee_name' => $orderItem->attendee_name,
                'ticket_type'   => $orderItem->ticket->name ?? '—',
                'checked_in_at' => $orderItem->checked_in_at?->format('h:i A'),
            ]);
        }

        // Valid — check in
        $orderItem->update([
            'is_checked_in'  => true,
            'checked_in_at'  => now(),
        ]);

        return response()->json([
            'status'        => 'success',
            'message'       => 'Check-in successful!',
            'attendee_name' => $orderItem->attendee_name,
            'ticket_type'   => $orderItem->ticket->name ?? '—',
            'ticket_code'   => $orderItem->ticket_code,
        ]);
    }
}
