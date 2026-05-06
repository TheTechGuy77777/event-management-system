<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalEvents = Event::where('user_id', $user->id)->count();

        $totalTicketsSold = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->whereHas('event', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            })->where('payment_status', 'completed');
        })->count();

        $totalRevenue = Order::whereHas('event', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('payment_status', 'completed')->sum('manager_earnings');

        $upcomingEvents = Event::where('user_id', $user->id)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->count();

        $recentEvents = Event::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'upcomingEvents',
            'recentEvents'
        ));
    }
}
