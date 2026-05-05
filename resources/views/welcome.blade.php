@extends('layouts.public')

@section('title', 'EventPlug — Discover Amazing Events')

@section('content')

    <!-- ===== HERO SECTION ===== -->
    <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-20 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-96 h-96 bg-amber-600/5 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-500/3 rounded-full blur-3xl">
            </div>
        </div>

        <div class="absolute inset-0 z-0 opacity-20"
            style="background-image: linear-gradient(rgba(245,158,11,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(245,158,11,0.1) 1px, transparent 1px); background-size: 50px 50px;">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 glass-gold px-4 py-2 rounded-full text-amber-400 text-sm font-medium mb-8">
                <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                Africa's Premier Event Ticketing Platform
            </div>

            <h1 class="font-display text-5xl sm:text-6xl lg:text-8xl leading-none tracking-tight mb-6">
                <span class="text-white">Your Next</span><br>
                <span class="gold-text">Unforgettable</span><br>
                <span class="text-white">Experience</span>
            </h1>

            <p class="text-gray-400 text-lg sm:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                Discover concerts, conferences, parties and more — or create your own event and start selling tickets in
                minutes.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                <a href="#events" class="btn-gold px-8 py-4 rounded-2xl text-black font-semibold text-lg w-full sm:w-auto">
                    <i class="fa-solid fa-compass mr-2"></i>Explore Events
                </a>
                <a href="{{ route('register') }}"
                    class="btn-outline-gold px-8 py-4 rounded-2xl font-semibold text-lg w-full sm:w-auto">
                    <i class="fa-solid fa-plus mr-2"></i>Create Your Event
                </a>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-8 sm:gap-16">
                <div class="text-center">
                    <div class="font-display text-3xl gold-text">500+</div>
                    <div class="text-gray-500 text-sm mt-1">Events Created</div>
                </div>
                <div class="hidden sm:block w-px h-10 bg-white/10"></div>
                <div class="text-center">
                    <div class="font-display text-3xl gold-text">50K+</div>
                    <div class="text-gray-500 text-sm mt-1">Tickets Sold</div>
                </div>
                <div class="hidden sm:block w-px h-10 bg-white/10"></div>
                <div class="text-center">
                    <div class="font-display text-3xl gold-text">6</div>
                    <div class="text-gray-500 text-sm mt-1">Countries</div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-gray-600">
            <span class="text-xs">Scroll to explore</span>
            <div class="w-px h-8 bg-gradient-to-b from-amber-400/50 to-transparent animate-pulse"></div>
        </div>
    </section>

    <!-- ===== SEARCH SECTION ===== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="glass rounded-3xl p-6 lg:p-8 border border-white/5">
            <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2 relative">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="text" name="search" placeholder="Search events..." value="{{ request('search') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                </div>

                <div class="relative">
                    <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <select name="country"
                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-400 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">All Locations</option>
                        <option value="Nigeria" {{ request('country') == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                        <option value="Ghana" {{ request('country') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                        <option value="Kenya" {{ request('country') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                        <option value="South Africa" {{ request('country') == 'South Africa' ? 'selected' : '' }}>South
                            Africa</option>
                        <option value="Rwanda" {{ request('country') == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                        <option value="United Kingdom" {{ request('country') == 'United Kingdom' ? 'selected' : '' }}>United
                            Kingdom</option>
                    </select>
                </div>

                <div class="relative">
                    <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <select name="category"
                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-400 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach ($categories ?? [] as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-gold px-6 py-3 rounded-xl text-black font-semibold text-sm">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i>Search
                </button>
            </form>
        </div>
    </section>

    <!-- ===== EVENTS GRID ===== -->
    <section id="events" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="font-display text-3xl lg:text-4xl text-white">
                    Upcoming <span class="gold-text">Events</span>
                </h2>
                <p class="text-gray-500 mt-2 text-sm">Discover what's happening near you</p>
            </div>
        </div>

        @if (isset($events) && $events->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($events as $event)
                    <a href="/events/{{ $event->slug }}"
                        class="group glass rounded-2xl overflow-hidden hover:border-amber-400/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-400/5">
                        <div class="relative h-48 overflow-hidden bg-white/5">
                            @if ($event->cover_image)
                                <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-calendar-star text-amber-400/20 text-5xl"></i>
                                </div>
                            @endif
                            @if ($event->category)
                                <div class="absolute top-3 left-3">
                                    <span class="glass-gold px-3 py-1 rounded-full text-amber-400 text-xs font-medium">
                                        {{ $event->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3
                                class="text-white font-semibold text-sm mb-2 line-clamp-2 group-hover:text-amber-400 transition-colors duration-200">
                                {{ $event->name }}
                            </h3>
                            <div class="flex items-center gap-2 text-gray-500 text-xs mb-2">
                                <i class="fa-solid fa-calendar text-amber-400/60"></i>
                                <span>{{ $event->start_date?->format('D, d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-500 text-xs mb-4">
                                <i class="fa-solid fa-location-dot text-amber-400/60"></i>
                                <span class="truncate">{{ $event->is_virtual ? 'Virtual Event' : $event->location }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                @php $minPrice = $event->tickets->where('ticket_type', 'paid')->min('price'); @endphp
                                @if ($minPrice)
                                    <span class="text-amber-400 font-semibold text-sm">From
                                        ₦{{ number_format($minPrice) }}</span>
                                @else
                                    <span class="text-green-400 font-semibold text-sm">Free</span>
                                @endif
                                <span
                                    class="glass px-3 py-1 rounded-full text-gray-400 text-xs group-hover:text-amber-400 transition-all duration-200">
                                    Get Tickets <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-12 flex justify-center">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-24">
                <div class="w-20 h-20 glass rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-calendar-xmark text-amber-400/40 text-3xl"></i>
                </div>
                <h3 class="text-white font-semibold text-xl mb-2">No Events Yet</h3>
                <p class="text-gray-500 text-sm mb-8">Be the first to create an amazing event on EventPlug</p>
                <a href="{{ route('register') }}"
                    class="btn-gold px-6 py-3 rounded-xl text-black font-semibold text-sm inline-block">
                    Create the First Event
                </a>
            </div>
        @endif
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section id="how-it-works" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-amber-500/3 rounded-full blur-3xl">
            </div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 glass-gold px-4 py-2 rounded-full text-amber-400 text-sm font-medium mb-4">
                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                    Simple & Fast
                </div>
                <h2 class="font-display text-3xl lg:text-5xl text-white">
                    How <span class="gold-text">EventPlug</span> Works
                </h2>
                <p class="text-gray-500 mt-4 max-w-xl mx-auto">From idea to sold-out event in three simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([['icon' => 'fa-user-plus', 'step' => '01', 'title' => 'Create Your Account', 'desc' => 'Register as an Event Manager in seconds. No complex setup — just your name, email, and you\'re in.'], ['icon' => 'fa-calendar-plus', 'step' => '02', 'title' => 'Set Up Your Event', 'desc' => 'Use our guided wizard to add event details, upload a cover image, and create ticket types with pricing.'], ['icon' => 'fa-share-nodes', 'step' => '03', 'title' => 'Share & Sell', 'desc' => 'Publish your event and share your unique link or QR code. Tickets sell automatically while you focus on the event.']] as $item)
                    <div
                        class="glass rounded-3xl p-8 relative group hover:border-amber-400/20 transition-all duration-300">
                        <div
                            class="absolute top-6 right-6 font-display text-6xl text-white/3 group-hover:text-amber-400/10 transition-colors duration-300">
                            {{ $item['step'] }}
                        </div>
                        <div
                            class="w-14 h-14 gold-gradient rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid {{ $item['icon'] }} text-black text-xl"></i>
                        </div>
                        <h3 class="text-white font-semibold text-lg mb-3">{{ $item['title'] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== CTA SECTION ===== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="glass-gold rounded-3xl p-12 lg:p-16 text-center relative overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-px bg-gradient-to-r from-transparent via-amber-400/50 to-transparent">
            </div>
            <div class="relative z-10">
                <h2 class="font-display text-3xl lg:text-5xl text-white mb-4">
                    Ready to Create Your <span class="gold-text">Next Event?</span>
                </h2>
                <p class="text-gray-400 text-lg mb-8 max-w-xl mx-auto">
                    Join hundreds of event organizers already using EventPlug to sell tickets and grow their audience.
                </p>
                <a href="{{ route('register') }}"
                    class="btn-gold px-10 py-4 rounded-2xl text-black font-bold text-lg inline-block">
                    Get Started for Free <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

@endsection
