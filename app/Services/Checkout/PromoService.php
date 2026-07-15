<?php

namespace App\Services\Checkout;

use App\Models\PromoCode;

class PromoService
{
    public function findForEvent(string $code, int $eventId): ?PromoCode
    {
        return PromoCode::where('code', strtoupper($code))
            ->where('event_id', $eventId)
            ->first();
    }

    public function validate(string $code, int $eventId, float $amount): array
    {
        $promo = $this->findForEvent($code, $eventId);

        if (! $promo) {
            return [
                'valid' => false,
                'message' => 'Invalid promo code.',
                'promo' => null,
            ];
        }

        if (! $promo->isValid()) {
            return [
                'valid' => false,
                'message' => 'This promo code has expired or reached its usage limit.',
                'promo' => $promo,
            ];
        }

        $discount = $promo->calculateDiscount($amount);

        return [
            'valid' => true,
            'message' => 'Promo code applied successfully!',
            'promo' => $promo,
            'discount' => $discount,
            'new_total' => max(0, $amount - $discount),
        ];
    }
}
