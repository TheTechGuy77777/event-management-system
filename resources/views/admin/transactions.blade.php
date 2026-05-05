@extends('layouts.admin')

@section('title', 'Transactions — EventPlug')
@section('page-title', 'Transactions')
@section('page-subtitle', 'All ticket purchases across the platform')

@section('content')

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-transfer text-black text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $transactions->total() }}</div>
                    <div class="text-gray-500 text-xs">Total Transactions</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-naira-sign text-green-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">
                        ₦{{ number_format($totalRevenue) }}
                    </div>
                    <div class="text-gray-500 text-xs">Total Revenue</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-amber-500/20 border border-amber-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-hand-holding-dollar text-amber-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">
                        ₦{{ number_format($totalCommission) }}
                    </div>
                    <div class="text-gray-500 text-xs">Platform Commission</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Buyer
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Event
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Organizer
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Amount
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                            Commission</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Gateway
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-white/2 transition-colors">

                            <!-- Buyer -->
                            <td class="px-6 py-4">
                                <p class="text-white text-sm font-medium">{{ $transaction->buyer_name }}</p>
                                <p class="text-gray-500 text-xs mt-0.5">{{ $transaction->buyer_email }}</p>
                            </td>

                            <!-- Event -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm truncate max-w-[150px]">
                                    {{ $transaction->event->name ?? '—' }}
                                </p>
                            </td>

                            <!-- Organizer -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">
                                    {{ $transaction->event->user->name ?? '—' }}
                                </p>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4">
                                <p class="text-amber-400 text-sm font-semibold">
                                    ₦{{ number_format($transaction->total_amount) }}
                                </p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    Manager: ₦{{ number_format($transaction->manager_earnings) }}
                                </p>
                            </td>

                            <!-- Commission -->
                            <td class="px-6 py-4">
                                <p class="text-green-400 text-sm font-semibold">
                                    ₦{{ number_format($transaction->platform_commission) }}
                                </p>
                            </td>

                            <!-- Gateway -->
                            <td class="px-6 py-4">
                                <span class="glass px-3 py-1 rounded-full text-gray-400 text-xs capitalize">
                                    {{ $transaction->payment_gateway ?? '—' }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">
                                    {{ $transaction->created_at->format('d M Y') }}
                                </p>
                                <p class="text-gray-600 text-xs mt-0.5">
                                    {{ $transaction->created_at->format('h:i A') }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-money-bill-transfer text-amber-400/30 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">No transactions yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

@endsection
