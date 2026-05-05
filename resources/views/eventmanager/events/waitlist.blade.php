@extends('layouts.dashboard')

@section('title', 'Waitlist — ' . $event->name)
@section('page-title', 'Waitlist')
@section('page-subtitle', $event->name)

@section('content')

    <!-- Back -->
    <div class="mb-6">
        <a href="{{ route('dashboard.events.index') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>Back to My Events
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-bell text-black text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $waitlists->total() }}</div>
                    <div class="text-gray-500 text-xs">Total Waiting</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-green-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">
                        {{ $waitlists->getCollection()->where('is_notified', true)->count() }}
                    </div>
                    <div class="text-gray-500 text-xs">Notified</div>
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
                        {{ $waitlists->getCollection()->where('is_notified', false)->count() }}
                    </div>
                    <div class="text-gray-500 text-xs">Pending</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Waitlist Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-white/5">
            <h2 class="text-white font-semibold">Waitlist Entries</h2>
        </div>

        @if ($waitlists->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">#
                            </th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Guest
                            </th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Ticket</th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Joined</th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Status</th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach ($waitlists as $index => $entry)
                            <tr class="hover:bg-white/2 transition-colors">

                                <!-- Position -->
                                <td class="px-6 py-4">
                                    <span class="text-gray-500 text-sm">#{{ $index + 1 }}</span>
                                </td>

                                <!-- Guest -->
                                <td class="px-6 py-4">
                                    <p class="text-white text-sm font-medium">{{ $entry->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $entry->email }}</p>
                                </td>

                                <!-- Ticket -->
                                <td class="px-6 py-4">
                                    <p class="text-gray-300 text-sm">{{ $entry->ticket->name ?? '—' }}</p>
                                </td>

                                <!-- Joined -->
                                <td class="px-6 py-4">
                                    <p class="text-gray-300 text-sm">{{ $entry->created_at->format('d M Y') }}</p>
                                    <p class="text-gray-600 text-xs">{{ $entry->created_at->diffForHumans() }}</p>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @if ($entry->is_notified && $entry->hasPriorityWindow())
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full text-amber-400 bg-amber-500/10 border border-amber-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                            Priority Window Active
                                        </span>
                                    @elseif($entry->is_notified)
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full text-green-400 bg-green-500/10 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                            Notified
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full text-gray-400 bg-white/5 border border-white/10">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Waiting
                                        </span>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4">
                                    @if (!$entry->is_notified || !$entry->hasPriorityWindow())
                                        <form method="POST"
                                            action="{{ route('dashboard.events.waitlist.notify', [$event, $entry]) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn-gold px-4 py-1.5 rounded-lg text-black font-semibold text-xs">
                                                <i class="fa-solid fa-paper-plane mr-1"></i>Notify
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-600 text-xs">Window active</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($waitlists->hasPages())
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $waitlists->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-bell text-amber-400/30 text-2xl"></i>
                </div>
                <p class="text-gray-500 text-sm">No one on the waitlist yet.</p>
            </div>
        @endif
    </div>

@endsection
