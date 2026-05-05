<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalManagers = User::where('role', 'event_manager')->count();
        $totalEvents = Event::count();
        $totalPublished = Event::where('status', 'published')->count();
        $totalRevenue = Order::where('payment_status', 'completed')->sum('platform_commission');
        $totalTicketsSold = Order::where('payment_status', 'completed')->count();

        $recentTransactions = Order::with(['event.user'])
            ->where('payment_status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        $recentManagers = User::where('role', 'event_manager')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalManagers',
            'totalEvents',
            'totalPublished',
            'totalRevenue',
            'totalTicketsSold',
            'recentTransactions',
            'recentManagers'
        ));
    }
}
