@extends('layouts.admin')

@section('title', 'Transactions')
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
                    <div class="text-white font-bold text-xl">₦{{ number_format($totalRevenue) }}</div>
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
                    <div class="text-white font-bold text-xl">₦{{ number_format($totalCommission) }}</div>
                    <div class="text-gray-500 text-xs">Platform Commission</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass rounded-2xl p-6 mb-6">
        <form method="GET" action="{{ route('admin.transactions') }}"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <!-- Search -->
            <div class="lg:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search reference, buyer name or email..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
            </div>

            <!-- Gateway Filter -->
            <div class="relative">
                <i class="fa-solid fa-credit-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <select name="gateway"
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                    <option value="">All Gateways</option>
                    <option value="paystack" {{ request('gateway') == 'paystack' ? 'selected' : '' }}>Paystack</option>
                    <option value="monnify" {{ request('gateway') == 'monnify' ? 'selected' : '' }}>Monnify</option>
                </select>
            </div>

            <!-- Manager Filter -->
            <div class="relative">
                <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <select name="manager_id"
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                    <option value="">All Managers</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="btn-gold px-6 py-3 rounded-xl text-black font-semibold text-sm">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>Filter
            </button>

            <!-- Date Range -->
            <div class="relative">
                <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
            </div>

            <div class="relative">
                <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
            </div>

            <!-- Clear Filters -->
            @if (request()->hasAny(['search', 'gateway', 'manager_id', 'date_from', 'date_to']))
                <div class="sm:col-span-2 lg:col-span-2 flex items-center gap-3">
                    <a href="{{ route('admin.transactions') }}"
                        class="btn-outline-gold px-4 py-3 rounded-xl font-semibold text-sm text-center flex-1">
                        <i class="fa-solid fa-times mr-2"></i>Clear Filters
                    </a>
                    <a href="{{ route('admin.transactions', array_merge(request()->all(), ['export' => 'csv'])) }}"
                        class="glass px-4 py-3 rounded-xl text-amber-400 font-semibold text-sm text-center flex-1 hover:border-amber-400/30 transition-colors">
                        <i class="fa-solid fa-file-csv mr-2"></i>Export CSV
                    </a>
                </div>
            @else
                <div class="flex items-center">
                    <a href="{{ route('admin.transactions', ['export' => 'csv']) }}"
                        class="glass px-4 py-3 rounded-xl text-amber-400 font-semibold text-sm text-center w-full hover:border-amber-400/30 transition-colors">
                        <i class="fa-solid fa-file-csv mr-2"></i>Export All CSV
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Active Filters Badge -->
    @if (request()->hasAny(['search', 'gateway', 'manager_id', 'date_from', 'date_to']))
        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <span class="text-gray-500 text-xs">Active filters:</span>
            @if (request('search'))
                <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs">
                    Search: "{{ request('search') }}"
                </span>
            @endif
            @if (request('gateway'))
                <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs">
                    Gateway: {{ ucfirst(request('gateway')) }}
                </span>
            @endif
            @if (request('manager_id'))
                <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs">
                    Manager: {{ $managers->find(request('manager_id'))?->name }}
                </span>
            @endif
            @if (request('date_from'))
                <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs">
                    From: {{ request('date_from') }}
                </span>
            @endif
            @if (request('date_to'))
                <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs">
                    To: {{ request('date_to') }}
                </span>
            @endif
            <span class="text-gray-500 text-xs">— {{ $transactions->total() }} result(s)</span>
        </div>
    @endif

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
                                <p class="text-gray-600 text-xs font-mono mt-0.5">{{ $transaction->payment_reference }}</p>
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
                                @if ($transaction->payment_gateway === 'paystack')
                                    <span
                                        class="bg-blue-500/10 border border-blue-500/20 px-3 py-1 rounded-full text-blue-400 text-xs font-medium">
                                        Paystack
                                    </span>
                                @elseif($transaction->payment_gateway === 'monnify')
                                    <span
                                        class="bg-green-500/10 border border-green-500/20 px-3 py-1 rounded-full text-green-400 text-xs font-medium">
                                        Monnify
                                    </span>
                                @else
                                    <span class="glass px-3 py-1 rounded-full text-gray-400 text-xs">
                                        {{ ucfirst($transaction->payment_gateway ?? '—') }}
                                    </span>
                                @endif
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
                                <p class="text-gray-500 text-sm">No transactions found.</p>
                                @if (request()->hasAny(['search', 'gateway', 'manager_id', 'date_from', 'date_to']))
                                    <a href="{{ route('admin.transactions') }}"
                                        class="text-amber-400 text-xs mt-2 inline-block hover:text-amber-300 transition-colors">
                                        Clear filters
                                    </a>
                                @endif
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
