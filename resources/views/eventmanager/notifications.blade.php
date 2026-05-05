@extends('layouts.dashboard')

@section('title', 'Notifications — EventPlug')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Stay updated on your events and account')

@section('content')

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            @if ($unreadCount > 0)
                <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs font-medium">
                    {{ $unreadCount }} unread
                </span>
            @endif
        </div>
        @if ($notifications->count() > 0)
            <form method="POST" action="{{ route('dashboard.notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-gray-400 hover:text-amber-400 text-sm transition-colors">
                    <i class="fa-solid fa-check-double mr-1"></i>Mark all as read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    @if ($notifications->count() > 0)
        <div class="space-y-3">
            @foreach ($notifications as $notification)
                <a href="{{ route('dashboard.notifications.show', $notification) }}"
                    class="block glass rounded-2xl p-5 hover:border-amber-400/20 transition-all duration-200
               {{ !$notification->is_read ? 'border-amber-400/20 bg-amber-400/5' : '' }}">
                    <div class="flex items-start gap-4">

                        <!-- Icon -->
                        <div
                            class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0
                        @if ($notification->type === 'success') bg-green-500/20 border border-green-500/30
                        @elseif($notification->type === 'warning') bg-yellow-500/20 border border-yellow-500/30
                        @elseif($notification->type === 'error') bg-red-500/20 border border-red-500/30
                        @else gold-gradient @endif">
                            @if ($notification->type === 'success')
                                <i class="fa-solid fa-circle-check text-green-400 text-sm"></i>
                            @elseif($notification->type === 'warning')
                                <i class="fa-solid fa-triangle-exclamation text-yellow-400 text-sm"></i>
                            @elseif($notification->type === 'error')
                                <i class="fa-solid fa-circle-exclamation text-red-400 text-sm"></i>
                            @else
                                <i class="fa-solid fa-bell text-black text-sm"></i>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-white text-sm font-semibold mb-1">
                                        {{ $notification->title }}
                                        @if (!$notification->is_read)
                                            <span class="w-2 h-2 bg-amber-400 rounded-full inline-block ml-2"></span>
                                        @endif
                                    </p>
                                    <p class="text-gray-400 text-sm leading-relaxed">
                                        {{ $notification->message }}
                                    </p>
                                </div>
                                <span class="text-gray-600 text-xs whitespace-nowrap flex-shrink-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="glass rounded-2xl p-16 text-center">
            <div class="w-20 h-20 glass rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-bell text-amber-400/30 text-3xl"></i>
            </div>
            <h3 class="text-white font-semibold text-xl mb-2">No notifications yet</h3>
            <p class="text-gray-500 text-sm">
                You'll receive notifications when tickets are sold, payouts are processed, and more.
            </p>
        </div>
    @endif

@endsection
