@extends('layouts.public')

@section('title', $event->name)

@section('content')

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-20px]"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-20px]"
            class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-[#1a1a1a] border border-green-500/30 text-green-400 px-5 py-4 rounded-2xl shadow-2xl max-w-sm">
            <i class="fa-solid fa-circle-check text-lg flex-shrink-0"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
            <button x-on:click="show = false"
                class="ml-2 text-green-400/50 hover:text-green-400 transition-colors flex-shrink-0">
                <i class="fa-solid fa-times text-xs"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-20px]"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-20px]"
            class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-[#1a1a1a] border border-red-500/30 text-red-400 px-5 py-4 rounded-2xl shadow-2xl max-w-sm">
            <i class="fa-solid fa-circle-exclamation text-lg flex-shrink-0"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
            <button x-on:click="show = false"
                class="ml-2 text-red-400/50 hover:text-red-400 transition-colors flex-shrink-0">
                <i class="fa-solid fa-times text-xs"></i>
            </button>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ===== LEFT COLUMN ===== -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Cover Image -->
                <div class="relative rounded-3xl overflow-hidden bg-white/5 aspect-video">
                    @if ($event->cover_image)
                        <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center min-h-[300px]">
                            <i class="fa-solid fa-calendar-star text-amber-400/20 text-8xl"></i>
                        </div>
                    @endif

                    <!-- Overlay badges -->
                    <div class="absolute top-4 left-4 flex items-center gap-2">
                        @if ($event->category)
                            <span class="glass-gold px-3 py-1.5 rounded-full text-amber-400 text-xs font-medium">
                                {{ $event->category->name }}
                            </span>
                        @endif
                        <span class="glass px-3 py-1.5 rounded-full text-gray-300 text-xs font-medium">
                            {{ $event->event_type }}
                        </span>
                    </div>

                    <!-- Status badge -->
                    @if ($event->status === 'published')
                        <div class="absolute top-4 right-4">
                            <span
                                class="bg-green-500/20 border border-green-500/30 px-3 py-1.5 rounded-full text-green-400 text-xs font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                Live
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Event Title & Meta -->
                <div class="glass rounded-3xl p-8">
                    <h1 class="text-white text-3xl lg:text-4xl font-bold mb-6">{{ $event->name }}</h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

                        <!-- Date -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-calendar text-black text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs mb-1">Date & Time</p>
                                <p class="text-white text-sm font-medium">
                                    {{ $event->start_date?->format('l, d F Y') }}
                                </p>
                                <p class="text-gray-400 text-xs mt-0.5">
                                    {{ $event->start_date?->format('h:i A') }}
                                    — {{ $event->end_date?->format('h:i A') }}
                                    ({{ $event->timezone }})
                                </p>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-location-dot text-blue-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs mb-1">Location</p>
                                @if ($event->event_mode === 'online')
                                    <p class="text-white text-sm font-medium">Online Event</p>
                                    <p class="text-gray-400 text-xs mt-0.5">Link sent after purchase</p>
                                @elseif ($event->event_mode === 'hybrid')
                                    <p class="text-white text-sm font-medium">{{ $event->location ?? 'TBA' }} + Online</p>
                                    <p class="text-gray-400 text-xs mt-0.5">{{ $event->country }} • Online link sent after
                                        purchase</p>
                                @else
                                    <p class="text-white text-sm font-medium">{{ $event->location ?? 'TBA' }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">{{ $event->country }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Organizer -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-purple-500/20 border border-purple-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-user text-purple-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs mb-1">Organizer</p>
                                <p class="text-white text-sm font-medium">
                                    {{ $event->user->organization_name ?? $event->user->name }}
                                </p>
                            </div>
                        </div>

                        <!-- Format -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-amber-500/20 border border-amber-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs mb-1">Event Format</p>
                                <p class="text-white text-sm font-medium">{{ $event->event_type }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    @if ($event->start_date && $event->start_date->isFuture())
                        <div class="glass-gold rounded-2xl p-5 mb-6">
                            <p class="text-amber-400 text-xs font-medium mb-3 text-center">
                                <i class="fa-solid fa-clock mr-1"></i>Event starts in
                            </p>
                            <div class="grid grid-cols-4 gap-3 text-center" id="countdown">
                                <div>
                                    <div class="font-display text-2xl text-white font-bold" id="days">00</div>
                                    <div class="text-gray-500 text-xs mt-1">Days</div>
                                </div>
                                <div>
                                    <div class="font-display text-2xl text-white font-bold" id="hours">00</div>
                                    <div class="text-gray-500 text-xs mt-1">Hours</div>
                                </div>
                                <div>
                                    <div class="font-display text-2xl text-white font-bold" id="minutes">00</div>
                                    <div class="text-gray-500 text-xs mt-1">Minutes</div>
                                </div>
                                <div>
                                    <div class="font-display text-2xl text-white font-bold" id="seconds">00</div>
                                    <div class="text-gray-500 text-xs mt-1">Seconds</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div>
                        <h2 class="text-white font-semibold text-lg mb-4">About This Event</h2>
                        <div class="text-gray-400 text-sm leading-relaxed prose prose-invert max-w-none">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Lineup -->
                @if ($event->lineup->count() > 0)
                    <div class="glass rounded-3xl p-8">
                        <h2 class="text-white font-semibold text-lg mb-6">
                            <i class="fa-solid fa-microphone text-amber-400 mr-2"></i>Lineup
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($event->lineup as $member)
                                <div
                                    class="glass rounded-2xl p-4 text-center hover:border-amber-400/20 transition-all duration-200">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white/5 mx-auto mb-3">
                                        @if ($member->photo)
                                            <img src="{{ asset('storage/' . $member->photo) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fa-solid fa-user text-amber-400/30 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-white text-sm font-semibold">{{ $member->name }}</p>
                                    <p class="text-amber-400 text-xs mt-1">{{ $member->role }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Social Links -->
                @if ($event->instagram || $event->twitter || $event->facebook || $event->website)
                    <div class="glass rounded-3xl p-6">
                        <h2 class="text-white font-semibold text-sm mb-4">Follow This Event</h2>
                        <div class="flex items-center gap-3">
                            @if ($event->instagram)
                                <a href="https://instagram.com/{{ ltrim($event->instagram, '@') }}" target="_blank"
                                    class="flex items-center gap-2 glass px-4 py-2 rounded-xl text-gray-400 hover:text-pink-400 text-sm transition-colors">
                                    <i class="fa-brands fa-instagram"></i>
                                    Instagram
                                </a>
                            @endif
                            @if ($event->twitter)
                                <a href="https://twitter.com/{{ ltrim($event->twitter, '@') }}" target="_blank"
                                    class="flex items-center gap-2 glass px-4 py-2 rounded-xl text-gray-400 hover:text-sky-400 text-sm transition-colors">
                                    <i class="fa-brands fa-x-twitter"></i>
                                    Twitter
                                </a>
                            @endif
                            @if ($event->facebook)
                                <a href="{{ $event->facebook }}" target="_blank"
                                    class="flex items-center gap-2 glass px-4 py-2 rounded-xl text-gray-400 hover:text-blue-400 text-sm transition-colors">
                                    <i class="fa-brands fa-facebook"></i>
                                    Facebook
                                </a>
                            @endif
                            @if ($event->website)
                                <a href="{{ $event->website }}" target="_blank"
                                    class="flex items-center gap-2 glass px-4 py-2 rounded-xl text-gray-400 hover:text-amber-400 text-sm transition-colors">
                                    <i class="fa-solid fa-globe"></i>
                                    Website
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- ===== RIGHT COLUMN — TICKETS ===== -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4 relative z-10">

                    <!-- Tickets Card -->
                    <div class="glass rounded-3xl p-6 relative z-10">
                        <h2 class="text-white font-semibold text-lg mb-5">
                            <i class="fa-solid fa-ticket text-amber-400 mr-2"></i>Get Tickets
                        </h2>

                        @if ($event->tickets->where('is_active', true)->count() > 0)
                            <div class="space-y-3">
                                @foreach ($event->tickets->where('is_active', true) as $ticket)
                                    @if ($ticket->ticket_type !== 'invite_only')
                                        <div
                                            class="glass rounded-2xl p-4 hover:border-amber-400/20 transition-all duration-200
                                {{ $ticket->isSoldOut() ? 'opacity-60' : '' }}">

                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <p class="text-white text-sm font-semibold">{{ $ticket->name }}</p>
                                                    @if ($ticket->description)
                                                        <p class="text-gray-500 text-xs mt-0.5">{{ $ticket->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-right flex-shrink-0 ml-3">
                                                    @if ($ticket->ticket_type === 'free')
                                                        <span class="text-green-400 font-bold text-sm">Free</span>
                                                    @else
                                                        <span class="text-amber-400 font-bold text-sm">
                                                            ₦{{ number_format($ticket->price) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Perks -->
                                            @if ($ticket->perks->count() > 0)
                                                <ul class="space-y-1 mb-3">
                                                    @foreach ($ticket->perks as $perk)
                                                        <li class="flex items-center gap-2 text-gray-500 text-xs">
                                                            <i class="fa-solid fa-check text-amber-400/60 text-xs"></i>
                                                            {{ $perk->perk }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            <!-- Availability -->
                                            <div class="flex items-center justify-between">
                                                @if ($ticket->isSoldOut())
                                                    <span class="text-red-400 text-xs font-medium">Sold Out</span>
                                                    <button
                                                        onclick="openWaitlist({{ $ticket->id }}, '{{ $ticket->name }}')"
                                                        class="glass px-3 py-1.5 rounded-lg text-amber-400 text-xs hover:border-amber-400/30 transition-colors">
                                                        Join Waitlist
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">
                                                        {{ $ticket->remainingQuantity() }} left
                                                    </span>
                                                    <a href="{{ route('checkout', ['slug' => $event->slug, 'ticket' => $ticket->id]) }}"
                                                        style="background: linear-gradient(135deg, #f59e0b, #d97706); color: black; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none;">
                                                        Get Ticket
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm">No tickets available yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Share Event -->
                    <div class="glass rounded-3xl p-6">
                        <h3 class="text-white font-semibold text-sm mb-4">Share This Event</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <input type="text" value="{{ url('/events/' . $event->slug) }}" readonly id="event-url"
                                class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-gray-400 text-xs focus:outline-none truncate">
                            <button onclick="copyLink()"
                                class="w-9 h-9 gold-gradient rounded-xl flex items-center justify-center text-black flex-shrink-0 hover:opacity-90 transition-opacity">
                                <i class="fa-solid fa-copy text-xs"></i>
                            </button>
                        </div>
                        <p class="text-green-400 text-xs hidden" id="copy-success">
                            <i class="fa-solid fa-check mr-1"></i>Link copied!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Waitlist Modal -->
    <div id="waitlist-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeWaitlist()"></div>
        <div class="glass rounded-3xl p-8 w-full max-w-md relative z-10 border border-amber-400/20">

            <div class="text-center mb-6">
                <div class="w-14 h-14 glass-gold rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-bell text-amber-400 text-xl"></i>
                </div>
                <h3 class="text-white font-bold text-xl mb-1">Join Waitlist</h3>
                <p class="text-gray-500 text-sm">
                    We'll notify you when a <span class="text-amber-400" id="waitlist-ticket-name"></span> ticket becomes
                    available.
                </p>
            </div>

            @if (session('success'))
                <div class="glass-gold rounded-xl p-4 mb-4 text-amber-400 text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('waitlist.store', $event->slug) }}" class="space-y-4">
                @csrf
                <input type="hidden" name="ticket_id" id="waitlist-ticket-id">

                <div>
                    <label class="text-gray-400 text-sm font-medium mb-2 block">Full Name</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" name="name" placeholder="John Doe" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                    </div>
                </div>

                <div>
                    <label class="text-gray-400 text-sm font-medium mb-2 block">Email Address</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="email" name="email" placeholder="you@example.com" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                    </div>
                </div>

                <div class="glass-gold rounded-xl p-4">
                    <p class="text-amber-400 text-xs leading-relaxed">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        When a ticket becomes available, you'll get a <strong>30-minute priority booking window</strong> to
                        complete your purchase.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeWaitlist()"
                        class="flex-1 btn-outline-gold py-3 rounded-xl font-semibold text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 btn-gold py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-bell mr-2"></i>Notify Me
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Countdown Timer
        @if ($event->start_date && $event->start_date->isFuture())
            const eventDate = new Date('{{ $event->start_date->toISOString() }}');

            function updateCountdown() {
                const now = new Date();
                const diff = eventDate - now;

                if (diff <= 0) {
                    document.getElementById('countdown').innerHTML =
                        '<p class="text-amber-400 text-sm col-span-4 text-center">Event has started!</p>';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = String(days).padStart(2, '0');
                document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
                document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        @endif

        // Copy link
        function copyLink() {
            const url = document.getElementById('event-url').value;
            navigator.clipboard.writeText(url).then(() => {
                const msg = document.getElementById('copy-success');
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 3000);
            });
        }

        //waitlist
        function openWaitlist(ticketId, ticketName) {
            document.getElementById('waitlist-ticket-id').value = ticketId;
            document.getElementById('waitlist-ticket-name').textContent = ticketName;
            document.getElementById('waitlist-modal').classList.remove('hidden');
            document.getElementById('waitlist-modal').classList.add('flex');
        }

        function closeWaitlist() {
            document.getElementById('waitlist-modal').classList.add('hidden');
            document.getElementById('waitlist-modal').classList.remove('flex');
        }
    </script>
@endpush
