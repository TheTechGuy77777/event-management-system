@extends('layouts.dashboard')

@section('title', $notification->title . ' — EventPlug')
@section('page-title', 'Notification')
@section('page-subtitle', $notification->created_at->format('d M Y, h:i A'))

@section('content')

    <!-- Back -->
    <div class="mb-6">
        <a href="{{ route('dashboard.notifications') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to Notifications
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-8">

            <!-- Icon & Type -->
            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0
                    @if ($notification->type === 'success') bg-green-500/20 border border-green-500/30
                    @elseif($notification->type === 'warning') bg-yellow-500/20 border border-yellow-500/30
                    @elseif($notification->type === 'error') bg-red-500/20 border border-red-500/30
                    @else gold-gradient @endif">
                    @if ($notification->type === 'success')
                        <i class="fa-solid fa-circle-check text-green-400 text-2xl"></i>
                    @elseif($notification->type === 'warning')
                        <i class="fa-solid fa-triangle-exclamation text-yellow-400 text-2xl"></i>
                    @elseif($notification->type === 'error')
                        <i class="fa-solid fa-circle-exclamation text-red-400 text-2xl"></i>
                    @else
                        <i class="fa-solid fa-bell text-black text-2xl"></i>
                    @endif
                </div>
                <div>
                    <span
                        class="text-xs font-medium px-3 py-1 rounded-full
                        @if ($notification->type === 'success') bg-green-500/10 text-green-400 border border-green-500/20
                        @elseif($notification->type === 'warning') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                        @elseif($notification->type === 'error') bg-red-500/10 text-red-400 border border-red-500/20
                        @else glass-gold text-amber-400 @endif">
                        {{ ucfirst($notification->type) }}
                    </span>
                    <p class="text-gray-500 text-xs mt-2">
                        {{ $notification->created_at->diffForHumans() }} •
                        {{ $notification->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-white text-2xl font-bold mb-4">
                {{ $notification->title }}
            </h1>

            <!-- Divider -->
            <div class="border-t border-white/5 mb-6"></div>

            <!-- Message -->
            <div class="text-gray-300 text-sm leading-relaxed">
                {{ $notification->message }}
            </div>

            <!-- Footer -->
            <div class="border-t border-white/5 mt-8 pt-6 flex items-center justify-between">
                <a href="{{ route('dashboard.notifications') }}"
                    class="btn-outline-gold px-5 py-2.5 rounded-xl font-semibold text-sm">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back
                </a>
                <span class="text-gray-600 text-xs">
                    <i class="fa-solid fa-circle-check text-green-400 mr-1"></i>
                    Read
                </span>
            </div>
        </div>
    </div>

@endsection
