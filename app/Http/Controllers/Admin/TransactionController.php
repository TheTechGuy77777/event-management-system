<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Order::with(['event.user'])
            ->where('payment_status', 'completed')
            ->latest()
            ->paginate(15);

        $totalRevenue = Order::where('payment_status', 'completed')->sum('total_amount');
        $totalCommission = Order::where('payment_status', 'completed')->sum('platform_commission');

        return view('admin.transactions', compact(
            'transactions',
            'totalRevenue',
            'totalCommission'
        ));
    }
}
