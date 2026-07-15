<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['event.user'])
            ->where('payment_status', 'completed');

        // Filter by gateway
        if ($request->gateway) {
            $query->where('payment_gateway', $request->gateway);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by event manager
        if ($request->manager_id) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('user_id', $request->manager_id);
            });
        }

        // Search by reference or buyer
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

        // For export
        if ($request->export === 'csv') {
            return $this->exportCsv($query->get());
        }

        // Get managers for filter dropdown
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

    private function exportCsv($transactions)
    {
        $filename = 'transactions-'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Reference',
                'Buyer Name',
                'Buyer Email',
                'Event',
                'Organizer',
                'Total Amount',
                'Commission',
                'Manager Earnings',
                'Gateway',
                'Date',
            ]);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->payment_reference,
                    $tx->buyer_name,
                    $tx->buyer_email,
                    $tx->event->name ?? '—',
                    $tx->event->user->name ?? '—',
                    $tx->total_amount,
                    $tx->platform_commission,
                    $tx->manager_earnings,
                    ucfirst($tx->payment_gateway),
                    $tx->created_at->format('d M Y h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
