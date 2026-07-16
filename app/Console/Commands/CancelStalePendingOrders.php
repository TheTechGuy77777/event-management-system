<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Console\Command;

class CancelStalePendingOrders extends Command
{
    protected $signature = 'orders:cancel-stale-pending {--hours=24 : Cancel pending orders older than this many hours}';

    protected $description = 'Mark stale pending orders as expired and preserve order items for audit';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = now()->subHours($hours);

        $orders = Order::where('payment_status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No stale pending orders found.');

            return 0;
        }

        foreach ($orders as $order) {
            $meta = $order->checkout_meta ?? [];
            $promoCode = $meta['promo_code'] ?? null;

            if ($promoCode) {
                PromoCode::where('code', $promoCode)->decrement('usage_count');
            }

            $order->update(['payment_status' => 'failed']);
        }

        $this->info("Marked {$orders->count()} stale pending order(s) as expired.");

        return 0;
    }
}
