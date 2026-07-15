<?php

namespace App\Services\Checkout;

use App\Models\Event;
use App\Models\PromoCode;
use App\Models\Ticket;

class OrderCalculationService
{
    public function calculateSubtotal(Ticket $ticket, int $quantity): float
    {
        return (float) $ticket->price * $quantity;
    }

    public function applyPromo(?PromoCode $promo, float $subtotal): array
    {
        if (! $promo || ! $promo->isValid()) {
            return [
                'discount' => 0.0,
                'new_subtotal' => $subtotal,
                'applied_promo' => null,
            ];
        }

        $discount = $promo->calculateDiscount($subtotal);
        $newSubtotal = max(0.0, $subtotal - $discount);

        return [
            'discount' => $discount,
            'new_subtotal' => $newSubtotal,
            'applied_promo' => $promo,
        ];
    }

    public function calculateTotals(Event $event, float $subtotal): array
    {
        $commissionRate = (float) $event->commission_rate / 100;
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
