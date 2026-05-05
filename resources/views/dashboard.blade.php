@extends('layouts.dashboard')

@section('title', 'Dashboard — EventPlug')
@section('page-title', 'Overview')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . '!')

@section('content')

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <!-- Total Events -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 gold-gradient rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-calendar-days text-black text-sm"></i>
                </div>
                <span class="text-green-400 text-xs font-medium glass px-2 py-1 rounded-full">All Time</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">{{ $totalEvents }}</div>
            <div class="text-gray-500 text-sm">Total Events Created</div>
        </div>

        <!-- Upcoming Events -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-clock text-blue-400 text-sm"></i>
                </div>
                <span class="text-blue-400 text-xs font-medium glass px-2 py-1 rounded-full">Live</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">{{ $upcomingEvents }}</div>
            <div class="text-gray-500 text-sm">Upcoming Events</div>
        </div>

        <!-- Tickets Sold -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 bg-purple-500/20 border border-purple-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-ticket text-purple-400 text-sm"></i>
                </div>
                <span class="text-purple-400 text-xs font-medium glass px-2 py-1 rounded-full">Total</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">{{ $totalTicketsSold }}</div>
            <div class="text-gray-500 text-sm">Tickets Sold</div>
        </div>

        <!-- Revenue -->
        <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-11 h-11 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-naira-sign text-green-400 text-sm"></i>
                </div>
                <span class="text-green-400 text-xs font-medium glass px-2 py-1 rounded-full">Earned</span>
            </div>
            <div class="font-display text-3xl text-white mb-1">₦{{ number_format($totalRevenue) }}</div>
            <div class="text-gray-500 text-sm">Total Revenue</div>
        </div>
    </div>

    <!-- Recent Events + Quick Actions -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Recent Events -->
        <div class="xl:col-span-2 glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-semibold">Recent Events</h2>
                <a href="{{ route('dashboard.events.index') }}"
                    class="text-amber-400 text-sm hover:text-amber-300 transition-colors">
                    View all <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>

            @if ($recentEvents->count() > 0)
                <div class="space-y-3">
                    @foreach ($recentEvents as $event)
                        <div
                            class="flex items-center gap-4 p-4 bg-white/2 rounded-xl hover:bg-white/4 transition-colors group">
                            <!-- Image -->
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                                @if ($event->cover_image)
                                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-calendar text-amber-400/30 text-lg"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-white text-sm font-medium truncate group-hover:text-amber-400 transition-colors">
                                    {{ $event->name }}
                                </p>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ $event->start_date?->format('d M Y') ?? 'Date not set' }}
                                </p>
                            </div>

                            <!-- Status -->
                            <div class="flex-shrink-0">
                                @php
                                    $statusColors = [
                                        'published' => 'text-green-400 bg-green-500/10 border-green-500/20',
                                        'draft' => 'text-gray-400 bg-white/5 border-white/10',
                                        'ended' => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                                        'cancelled' => 'text-red-400 bg-red-500/10 border-red-500/20',
                                    ];
                                @endphp
                                <span
                                    class="text-xs px-3 py-1 rounded-full border {{ $statusColors[$event->status] ?? 'text-gray-400' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-calendar-plus text-amber-400/30 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">No events yet. Create your first event!</p>
                    <a href="{{ route('dashboard.events.create') }}"
                        class="btn-gold px-5 py-2 rounded-xl text-black font-semibold text-sm inline-block">
                        Create Event
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4">

            <!-- Create Event CTA -->
            <div class="glass-gold rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/5 rounded-full -translate-y-1/2 translate-x-1/2">
                </div>
                <div class="relative z-10">
                    <div class="w-11 h-11 gold-gradient rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-rocket text-black text-sm"></i>
                    </div>
                    <h3 class="text-white font-semibold mb-2">Create New Event</h3>
                    <p class="text-gray-500 text-xs mb-4 leading-relaxed">Set up your event in minutes and start selling
                        tickets.</p>
                    <a href="{{ route('dashboard.events.create') }}"
                        class="btn-gold px-4 py-2 rounded-xl text-black font-semibold text-sm inline-block w-full text-center">
                        Get Started <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="glass rounded-2xl p-6">
                <h3 class="text-white font-semibold mb-4 text-sm">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('dashboard.events.index') }}"
                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/4 transition-colors group">
                        <div
                            class="w-8 h-8 glass rounded-lg flex items-center justify-center group-hover:border-amber-400/20 transition-colors">
                            <i class="fa-solid fa-list text-amber-400/60 text-xs"></i>
                        </div>
                        <span class="text-gray-400 text-sm group-hover:text-white transition-colors">View All Events</span>
                        <i
                            class="fa-solid fa-chevron-right text-gray-600 text-xs ml-auto group-hover:text-amber-400 transition-colors"></i>
                    </a>
                    <a href="{{ route('dashboard.profile') }}"
                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/4 transition-colors group">
                        <div
                            class="w-8 h-8 glass rounded-lg flex items-center justify-center group-hover:border-amber-400/20 transition-colors">
                            <i class="fa-solid fa-user text-amber-400/60 text-xs"></i>
                        </div>
                        <span class="text-gray-400 text-sm group-hover:text-white transition-colors">Edit Profile</span>
                        <i
                            class="fa-solid fa-chevron-right text-gray-600 text-xs ml-auto group-hover:text-amber-400 transition-colors"></i>
                    </a>
                    <a href="{{ route('dashboard.account') }}"
                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/4 transition-colors group">
                        <div
                            class="w-8 h-8 glass rounded-lg flex items-center justify-center group-hover:border-amber-400/20 transition-colors">
                            <i class="fa-solid fa-building-columns text-amber-400/60 text-xs"></i>
                        </div>
                        <span class="text-gray-400 text-sm group-hover:text-white transition-colors">Bank Account</span>
                        <i
                            class="fa-solid fa-chevron-right text-gray-600 text-xs ml-auto group-hover:text-amber-400 transition-colors"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
