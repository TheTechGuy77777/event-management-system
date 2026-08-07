<?php

namespace App\Services\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function hasSufficientQuantity(Ticket $ticket, int $quantity): bool
    {
        return $ticket->remainingQuantity() >= $quantity;
    }

    public function incrementTicketQuantity(Ticket $ticket, int $quantity): bool
    {
        if (! $this->hasSufficientQuantity($ticket, $quantity)) {
            return false;
        }

        $ticket->increment('quantity_sold', $quantity);

        return true;
    }

    public function createOrderItems(
        Order $order,
        Ticket $ticket,
        int $quantity,
        array $attendees = [],
        ?string $buyerName = null,
        ?string $buyerEmail = null
    ): void {
        for ($i = 0; $i < $quantity; $i++) {
            $attendeeName = $attendees[$i]['name'] ?? $buyerName ?? '';
            $attendeeEmail = $attendees[$i]['email'] ?? $buyerEmail ?? '';

            $this->createSingleOrderItem($order, $ticket, $attendeeName, $attendeeEmail);
        }
    }

    private function generateUniqueTicketCode(): string
    {
        do {
            $code = 'EVT-' . strtoupper(Str::random(6));
        } while (OrderItem::where('ticket_code', $code)->exists());

        return $code;
    }

    private function generateQrCode(string $ticketCode): string
    {
        $qrPath = 'qrcodes/ticket-' . $ticketCode . '.svg';
        $fullPath = storage_path('app/public/' . $qrPath);

        Storage::disk('public')->makeDirectory('qrcodes');

        QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($ticketCode, $fullPath);

        return $qrPath;
    }

    private function createSingleOrderItem(Order $order, Ticket $ticket, string $attendeeName, string $attendeeEmail): void
    {
        $maxAttempts = 5;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $attempt++;
            $ticketCode = $this->generateUniqueTicketCode();
            $qrPath = $this->generateQrCode($ticketCode);

            try {
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

                return;
            } catch (QueryException $e) {
                if (str_contains($e->getMessage(), 'ticket_code') && $attempt < $maxAttempts) {
                    Storage::disk('public')->delete($qrPath);

                    continue;
                }

                throw $e;
            }
        }
    }
}
