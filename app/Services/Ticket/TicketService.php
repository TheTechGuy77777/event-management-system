<?php

namespace App\Services\Ticket;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function generateUniqueTicketCode(): string
    {
        do {
            $code = 'EVT-'.strtoupper(Str::random(6));
        } while (OrderItem::where('ticket_code', $code)->exists());

        return $code;
    }

    public function generateQrCode(string $ticketCode): string
    {
        $qrPath = 'qrcodes/ticket-'.$ticketCode.'.svg';
        $fullPath = storage_path('app/public/'.$qrPath);

        if (! file_exists(storage_path('app/public/qrcodes'))) {
            mkdir(storage_path('app/public/qrcodes'), 0755, true);
        }

        QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($ticketCode, $fullPath);

        return $qrPath;
    }

    public function createOrderItems(Order $order, Ticket $ticket, int $quantity, array $attendees, string $buyerName, string $buyerEmail): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            $attendeeName = $attendees[$i]['name'] ?? $buyerName;
            $attendeeEmail = $attendees[$i]['email'] ?? $buyerEmail;

            if (empty($attendeeName)) {
                $attendeeName = $buyerName;
            }
            if (empty($attendeeEmail)) {
                $attendeeEmail = $buyerEmail;
            }

            $ticketCode = $this->generateUniqueTicketCode();
            $qrPath = $this->generateQrCode($ticketCode);

            OrderItem::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
                'attendee_name' => $attendeeName,
                'attendee_email' => $attendeeEmail,
                'ticket_code' => $ticketCode,
                'unit_price' => $ticket->price,
                'is_checked_in' => false,
                'qr_code' => $qrPath,
            ]);
        }
    }

    public function incrementTicketQuantity(Ticket $ticket, int $quantity): void
    {
        $ticket->increment('quantity_sold', $quantity);
    }

    public function isSoldOut(Ticket $ticket): bool
    {
        return $ticket->isSoldOut();
    }

    public function hasSufficientQuantity(Ticket $ticket, int $quantity): bool
    {
        return $ticket->remainingQuantity() >= $quantity;
    }
}
