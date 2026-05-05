@extends('layouts.admin')

@section('title', 'All Events — EventPlug')
@section('page-title', 'All Events')
@section('page-subtitle', 'View all events across the platform')

@section('content')

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
        @foreach (['all' => 'All', 'published' => 'Published', 'draft' => 'Draft', 'ended' => 'Ended', 'cancelled' => 'Cancelled'] as $value => $label)
            <a href="{{ route('admin.events', ['status' => $value]) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex-shrink-0
           {{ request('status', 'all') === $value
               ? 'glass-gold text-amber-400 border border-amber-400/20'
               : 'glass text-gray-400 hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Events Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Event</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Organizer
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Date</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Status
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Tickets
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($events as $event)
                        <tr class="hover:bg-white/2 transition-colors group">

                            <!-- Event -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                                        @if ($event->cover_image)
                                            <img src="{{ asset('storage/' . $event->cover_image) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fa-solid fa-calendar text-amber-400/30"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-white text-sm font-medium truncate max-w-[180px] group-hover:text-amber-400 transition-colors">
                                            {{ $event->name }}
                                        </p>
                                        <p class="text-gray-500 text-xs mt-0.5">
                                            {{ $event->category->name ?? 'No category' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Organizer -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">{{ $event->user->name ?? '—' }}</p>
                                <p class="text-gray-500 text-xs mt-0.5">{{ $event->user->email ?? '—' }}</p>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">
                                    {{ $event->start_date?->format('d M Y') ?? 'Not set' }}
                                </p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    {{ $event->start_date?->format('h:i A') ?? '' }}
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'published' => [
                                            'color' => 'text-green-400 bg-green-500/10 border-green-500/20',
                                            'dot' => 'bg-green-400',
                                        ],
                                        'draft' => [
                                            'color' => 'text-gray-400 bg-white/5 border-white/10',
                                            'dot' => 'bg-gray-400',
                                        ],
                                        'ended' => [
                                            'color' => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                                            'dot' => 'bg-blue-400',
                                        ],
                                        'cancelled' => [
                                            'color' => 'text-red-400 bg-red-500/10 border-red-500/20',
                                            'dot' => 'bg-red-400',
                                        ],
                                    ];
                                    $config = $statusConfig[$event->status] ?? $statusConfig['draft'];
                                @endphp
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full border {{ $config['color'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>

                            <!-- Tickets -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">
                                    {{ $event->tickets->sum('quantity_sold') }}
                                    <span class="text-gray-600">/ {{ $event->tickets->sum('quantity') }}</span>
                                </p>
                                <p class="text-gray-500 text-xs mt-0.5">sold</p>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="/events/{{ $event->slug }}" target="_blank"
                                        class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                        title="View Public Page">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-calendar text-amber-400/30 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">No events found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($events->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $events->links() }}
            </div>
        @endif
    </div>

@endsection
