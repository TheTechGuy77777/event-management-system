@extends('layouts.dashboard')

@section('title', 'Attendees — ' . $event->name)
@section('page-title', 'Attendees')
@section('page-subtitle', $event->name)

@section('content')

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('dashboard.events.index') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to My Events
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-black text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $totalAttendees }}</div>
                    <div class="text-gray-500 text-xs">Total Attendees</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $totalCheckedIn }}</div>
                    <div class="text-gray-500 text-xs">Checked In</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock text-blue-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">
                        {{ $totalAttendees - $totalCheckedIn }}
                    </div>
                    <div class="text-gray-500 text-xs">Not Yet Arrived</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Export -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('dashboard.events.attendees', $event) }}"
            class="flex items-center gap-3 flex-1 max-w-md">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
            </div>
            <button type="submit" class="btn-gold px-4 py-2.5 rounded-xl text-black font-semibold text-sm">
                Search
            </button>
        </form>

        <a href="{{ route('dashboard.events.attendees.export', $event) }}"
            class="btn-outline-gold px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
            <i class="fa-solid fa-file-csv"></i>Export CSV
        </a>
    </div>

    <!-- Attendees Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Attendee
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Ticket
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Ticket
                            Code</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Amount
                            Paid</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Purchase
                            Date</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Check-In
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($attendees as $attendee)
                        <tr class="hover:bg-white/2 transition-colors">

                            <!-- Attendee -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
                                        <span class="text-black font-bold text-xs">
                                            {{ strtoupper(substr($attendee->attendee_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ $attendee->attendee_name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $attendee->attendee_email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Ticket Type -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">{{ $attendee->ticket->name ?? '—' }}</p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    {{ ucfirst($attendee->ticket->admission_type ?? '') }}
                                </p>
                            </td>

                            <!-- Ticket Code -->
                            <td class="px-6 py-4">
                                <span class="glass px-3 py-1 rounded-lg text-amber-400 text-xs font-mono">
                                    {{ $attendee->ticket_code }}
                                </span>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4">
                                <p class="text-amber-400 text-sm font-semibold">
                                    ₦{{ number_format($attendee->unit_price) }}
                                </p>
                            </td>

                            <!-- Purchase Date -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">
                                    {{ $attendee->created_at->format('d M Y') }}
                                </p>
                                <p class="text-gray-600 text-xs mt-0.5">
                                    {{ $attendee->created_at->format('h:i A') }}
                                </p>
                            </td>

                            <!-- Check-In Status -->
                            <td class="px-6 py-4">
                                @if ($attendee->is_checked_in)
                                    <div>
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full text-green-400 bg-green-500/10 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                            Checked In
                                        </span>
                                        <p class="text-gray-600 text-xs mt-1">
                                            {{ $attendee->checked_in_at?->format('h:i A') }}
                                        </p>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full text-gray-400 bg-white/5 border border-white/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Not Arrived
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-users text-amber-400/30 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">
                                    @if (request('search'))
                                        No attendees found matching "{{ request('search') }}"
                                    @else
                                        No attendees yet. Share your event to get ticket sales!
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($attendees->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $attendees->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

@endsection
