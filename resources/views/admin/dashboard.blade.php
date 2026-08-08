@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Platform overview and controls')

@section('content')

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <!-- Total Managers -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 gold-gradient rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-users text-black text-sm"></i>
                </div>
                <span class="text-amber-400 text-xs glass px-2 py-1 rounded-full">Registered</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">{{ $stats['totalManagers'] }}</div>
            <div class="text-gray-500 text-sm">Event Managers</div>
        </div>

        <!-- Total Events -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-calendar-days text-blue-400 text-sm"></i>
                </div>
                <span class="text-blue-400 text-xs glass px-2 py-1 rounded-full">{{ $stats['totalPublished'] }} Live</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">{{ $stats['totalEvents'] }}</div>
            <div class="text-gray-500 text-sm">Total Events</div>
        </div>

        <!-- Tickets Sold -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 bg-purple-500/20 border border-purple-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-ticket text-purple-400 text-sm"></i>
                </div>
                <span class="text-purple-400 text-xs glass px-2 py-1 rounded-full">Completed</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">{{ $stats['totalTicketsSold'] }}</div>
            <div class="text-gray-500 text-sm">Tickets Sold</div>
        </div>

        <!-- Platform Revenue -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-naira-sign text-green-400 text-sm"></i>
                </div>
                <span class="text-green-400 text-xs glass px-2 py-1 rounded-full">Commission</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">₦{{ number_format($stats['totalRevenue']) }}</div>
            <div class="text-gray-500 text-sm">Platform Revenue</div>
        </div>
    </div>

    <!-- Recent Transactions + Recent Managers -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Recent Transactions -->
        <div class="xl:col-span-2 glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-semibold">Recent Transactions</h2>
                <a href="{{ route('admin.transactions') }}"
                    class="text-amber-400 text-sm hover:text-amber-300 transition-colors">
                    View all <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>

            @if ($recentTransactions->count() > 0)
                <div class="space-y-3">
                    @foreach ($recentTransactions as $order)
                        <div class="flex items-center gap-4 p-4 bg-white/2 rounded-xl hover:bg-white/4 transition-colors">
                            <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-ticket text-black text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm font-medium truncate">
                                    {{ $order->event->name ?? 'Unknown Event' }}
                                </p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    {{ $order->buyer_name }} • {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-amber-400 text-sm font-semibold">
                                    ₦{{ number_format($order->total_amount) }}
                                </p>
                                <p class="text-gray-600 text-xs mt-0.5">
                                    Commission: ₦{{ number_format($order->platform_commission) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-money-bill-transfer text-amber-400/30 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">No transactions yet.</p>
                </div>
            @endif
        </div>

        <!-- Recent Managers -->
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-semibold">Recent Managers</h2>
                <a href="{{ route('admin.managers') }}"
                    class="text-amber-400 text-sm hover:text-amber-300 transition-colors">
                    View all <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>

            @if ($recentManagers->count() > 0)
                <div class="space-y-3">
                    @foreach ($recentManagers as $manager)
                        <div class="flex items-center gap-3 p-3 bg-white/2 rounded-xl hover:bg-white/4 transition-colors">
                            <div class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
                                <span class="text-black font-bold text-xs">
                                    {{ strtoupper(substr($manager->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm font-medium truncate">{{ $manager->name }}</p>
                                <p class="text-gray-500 text-xs truncate">{{ $manager->email }}</p>
                            </div>
                            @php
                                $statusColor = $manager->is_banned
                                    ? 'text-red-400'
                                    : ($manager->is_active
                                        ? 'text-green-400'
                                        : 'text-yellow-400');
                                $statusLabel = $manager->is_banned
                                    ? 'Banned'
                                    : ($manager->is_active
                                        ? 'Active'
                                        : 'Suspended');
                            @endphp
                            <span class="text-xs {{ $statusColor }} flex-shrink-0">{{ $statusLabel }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-sm">No managers yet.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
