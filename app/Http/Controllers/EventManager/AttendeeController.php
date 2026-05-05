<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class AttendeeController extends Controller
{
    public function index(Event $event)
    {
        // Verify ownership
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $attendees = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })
            ->with(['order', 'ticket'])
            ->when(request('search'), function ($q) {
                $q->where(function ($query) {
                    $query->where('attendee_name', 'like', '%' . request('search') . '%')
                        ->orWhere('attendee_email', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate(20);

        $totalCheckedIn = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })->where('is_checked_in', true)->count();

        $totalAttendees = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })->count();

        return view('eventmanager.events.attendees', compact(
            'event',
            'attendees',
            'totalCheckedIn',
            'totalAttendees'
        ));
    }

    public function export(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $attendees = OrderItem::whereHas('order', function ($q) use ($event) {
            $q->where('event_id', $event->id)
                ->where('payment_status', 'completed');
        })
            ->with(['order', 'ticket'])
            ->get();

        $filename = 'attendees-' . $event->slug . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($attendees) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Attendee Name',
                'Email',
                'Phone',
                'Ticket Type',
                'Ticket Code',
                'Amount Paid',
                'Purchase Date',
                'Check-In Status',
                'Check-In Time',
            ]);

            foreach ($attendees as $attendee) {
                fputcsv($file, [
                    $attendee->attendee_name,
                    $attendee->attendee_email,
                    $attendee->order->buyer_phone ?? '—',
                    $attendee->ticket->name ?? '—',
                    $attendee->ticket_code,
                    $attendee->unit_price,
                    $attendee->created_at->format('d M Y h:i A'),
                    $attendee->is_checked_in ? 'Checked In' : 'Not Arrived',
                    $attendee->checked_in_at?->format('d M Y h:i A') ?? '—',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
