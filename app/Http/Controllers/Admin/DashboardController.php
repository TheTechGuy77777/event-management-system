<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'totalManagers' => User::where('role', 'event_manager')->count(),
                'totalEvents' => Event::count(),
                'totalPublished' => Event::where('status', 'published')->count(),
                'totalRevenue' => Order::where('payment_status', 'completed')->sum('platform_commission'),
                'totalTicketsSold' => Order::where('payment_status', 'completed')->count(),
            ];
        });

        $recentTransactions = Cache::remember('admin_recent_transactions', 60, function () {
            return Order::with(['event.user'])
                ->where('payment_status', 'completed')
                ->latest()
                ->take(10)
                ->get();
        });

        $recentManagers = Cache::remember('admin_recent_managers', 300, function () {
            return User::where('role', 'event_manager')
                ->latest()
                ->take(5)
                ->get();
        });

        return view('admin.dashboard', compact(
            'stats',
            'recentTransactions',
            'recentManagers'
        ));
    }
}
