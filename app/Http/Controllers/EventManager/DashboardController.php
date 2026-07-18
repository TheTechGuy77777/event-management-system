<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = Cache::remember("dashboard_stats_{$user->id}", 300, function () use ($user) {
            return [
                'totalEvents' => Event::where('user_id', $user->id)->count(),
                'totalTicketsSold' => OrderItem::completedForManager($user->id)->count(),
                'totalRevenue' => Order::whereHas('event', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('payment_status', 'completed')->sum('manager_earnings'),
                'upcomingEvents' => Event::where('user_id', $user->id)
                    ->where('status', 'published')
                    ->where('start_date', '>', now())
                    ->count(),
            ];
        });

        $recentEvents = Cache::remember("dashboard_recent_events_{$user->id}", 300, function () use ($user) {
            return Event::where('user_id', $user->id)
                ->with(['category', 'tickets'])
                ->latest()
                ->take(5)
                ->get();
        });

        return view('dashboard', [
            'totalEvents' => $stats['totalEvents'],
            'totalTicketsSold' => $stats['totalTicketsSold'],
            'totalRevenue' => $stats['totalRevenue'],
            'upcomingEvents' => $stats['upcomingEvents'],
            'recentEvents' => $recentEvents,
        ]);
    }
}
