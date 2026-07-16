<?php

namespace App\Services\Checkout;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;

class OrderCalculationService
{
    public function calculateSubtotal(Ticket $ticket, int $quantity): float
    {
        return (float) $ticket->price * $quantity;
    }

    public function calculateTotals(Event $event, float $subtotal): array
    {
        $eventRate = (float) $event->commission_rate;
        $globalRate = (float) Cache::get('commission_rate', config('app.commission_rate', 5));
        $managerRate = $event->user?->custom_commission ?? 0;

        $commissionRate = $eventRate > 0 ? $eventRate : ($managerRate > 0 ? $managerRate : $globalRate);
        $commissionRate = $commissionRate / 100;

        $commission = $subtotal * $commissionRate;
        $managerEarnings = $subtotal - ($event->payment_model === 'manager_pays' ? $commission : 0);

        if ($event->payment_model === 'attendee_pays') {
            $total = $subtotal + $commission;
        } else {
            $total = $subtotal;
        }

        return [
            'commission' => $commission,
            'total' => $total,
            'manager_earnings' => $managerEarnings,
        ];
    }
}
