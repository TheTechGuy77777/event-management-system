@extends('layouts.public')

@section('title', 'Booking Confirmed — EventPlug')

@section('content')

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-green-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">

            <!-- Success Header -->
            <div class="text-center mb-8">
                <div
                    class="w-24 h-24 bg-green-500/20 border border-green-500/30 rounded-3xl flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="fa-solid fa-circle-check text-green-400 text-5xl"></i>
                </div>
                <h1 class="font-display text-4xl text-white font-bold mb-2">You're In! 🎉</h1>
                <p class="text-gray-400 text-lg">Your tickets have been confirmed.</p>
            </div>

            <!-- Order Details -->
            <div class="glass rounded-3xl p-8 mb-6">

                <!-- Event Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-white/5">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                        @if ($event->cover_image)
                            <img src="{{ asset('storage/' . $event->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-calendar text-amber-400/30 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg">{{ $event->name }}</h2>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $event->start_date?->format('D, d M Y • h:i A') }}
                        </p>
                    </div>
                </div>

                <!-- Order Reference -->
                <div class="glass-gold rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 text-sm">Order Reference</span>
                        <span class="text-amber-400 font-mono font-bold text-sm">
                            {{ $order->payment_reference }}
                        </span>
                    </div>
                </div>

                <!-- Tickets -->
                <h3 class="text-white font-semibold mb-4">Your Tickets</h3>
                <div class="space-y-3">
                    @foreach ($order->items as $item)
                        <div class="glass rounded-xl p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-ticket text-black text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ $item->attendee_name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $item->ticket->name ?? '—' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-amber-400 font-mono font-bold text-sm">{{ $item->ticket_code }}</p>
                                    <p class="text-gray-500 text-xs mt-0.5">₦{{ number_format($item->unit_price) }}</p>
                                </div>
                            </div>

                            <!-- QR Code -->
                            @if ($item->qr_code)
                                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                    <div>
                                        <p class="text-gray-400 text-xs mb-1">Scan at entrance</p>
                                        <p class="text-gray-600 text-xs">Present this QR code to check in</p>
                                    </div>
                                    <div class="bg-white p-2 rounded-xl">
                                        <img src="{{ asset('storage/' . $item->qr_code) }}" alt="Ticket QR Code"
                                            class="w-20 h-20">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="border-t border-white/5 mt-6 pt-6 flex items-center justify-between">
                    <span class="text-white font-semibold">Total Paid</span>
                    <span class="text-amber-400 font-bold text-xl">
                        ₦{{ number_format($order->total_amount) }}
                    </span>
                </div>
            </div>

            <!-- Email Notice -->
            <div class="glass rounded-2xl p-5 mb-6 flex items-start gap-3">
                <i class="fa-solid fa-envelope text-amber-400 mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-white text-sm font-medium">Check your email</p>
                    <p class="text-gray-500 text-xs mt-1 leading-relaxed">
                        Your ticket confirmation and unique QR codes have been sent to
                        <span class="text-amber-400">{{ $order->buyer_email }}</span>.
                        Present your ticket code at the entrance to gain access.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/events/{{ $event->slug }}"
                    class="btn-outline-gold flex-1 py-3 rounded-xl font-semibold text-sm text-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Event
                </a>
                <a href="{{ route('home') }}"
                    class="btn-gold flex-1 py-3 rounded-xl text-black font-semibold text-sm text-center">
                    <i class="fa-solid fa-compass mr-2"></i>Discover More Events
                </a>
            </div>
        </div>
    </div>

@endsection
