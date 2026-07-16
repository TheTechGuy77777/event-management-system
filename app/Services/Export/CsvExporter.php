<?php

namespace App\Services\Export;

use App\Models\Event;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter
{
    public function exportAttendees(Event $event, string $filename): StreamedResponse
    {
        $attendees = OrderItem::completedForEvent($event->id)
            ->with(['order', 'ticket'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($attendees) {
            $file = fopen('php://output', 'w');

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

    public function exportTransactions(Builder $query, string $filename): StreamedResponse
    {
        $transactions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Reference',
                'Buyer Name',
                'Buyer Email',
                'Event',
                'Organizer',
                'Total Amount',
                'Commission',
                'Manager Earnings',
                'Gateway',
                'Date',
            ]);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->payment_reference,
                    $tx->buyer_name,
                    $tx->buyer_email,
                    $tx->event->name ?? '—',
                    $tx->event->user->name ?? '—',
                    $tx->total_amount,
                    $tx->platform_commission,
                    $tx->manager_earnings,
                    ucfirst($tx->payment_gateway),
                    $tx->created_at->format('d M Y h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
