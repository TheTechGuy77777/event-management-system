<?php

namespace App\Services\Checkout;

use App\Models\PromoCode;

class PreparedOrder
{
    public function __construct(
        public float $subtotal,
        public ?PromoCode $promo,
        public float $discount,
        public float $newSubtotal,
        public array $totals,
        public array $orderData,
        public bool $isFree,
    ) {}
}
