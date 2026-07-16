<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\Export\CsvExporter;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private CsvExporter $csvExporter,
    ) {}

    public function index(Request $request)
    {
        $query = Order::with(['event.user'])
            ->where('payment_status', 'completed');

        if ($request->gateway) {
            $query->where('payment_gateway', $request->gateway);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->manager_id) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('user_id', $request->manager_id);
            });
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('payment_reference', 'like', '%'.$request->search.'%')
                    ->orWhere('buyer_name', 'like', '%'.$request->search.'%')
                    ->orWhere('buyer_email', 'like', '%'.$request->search.'%');
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $totalRevenue = $query->sum('total_amount');
        $totalCommission = $query->sum('platform_commission');

        if ($request->export === 'csv') {
            return $this->csvExporter->exportTransactions($query, 'transactions-'.now()->format('Y-m-d').'.csv');
        }

        $managers = User::where('role', 'event_manager')
            ->orderBy('name')
            ->get();

        return view('admin.transactions', compact(
            'transactions',
            'totalRevenue',
            'totalCommission',
            'managers'
        ));
    }
}
