@extends('layouts.public')

@section('title', 'About')

@section('content')

    <!-- Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <!-- Hero -->
        <div class="text-center mb-20">
            <div
                class="inline-flex items-center gap-2 glass-gold px-4 py-2 rounded-full text-amber-400 text-sm font-medium mb-6">
                <i class="fa-solid fa-bolt text-xs"></i>
                Our Story
            </div>
            <h1 class="font-display text-4xl lg:text-6xl text-white font-bold mb-6">
                Built for African<br>
                <span class="gold-text">Event Creators</span>
            </h1>
            <p class="text-gray-400 text-lg max-w-3xl mx-auto leading-relaxed">
                {{ config('app.name') }} was born out of frustration. We watched talented event organizers across Africa
                struggle with
                complicated, expensive, and unreliable ticketing platforms built for Western markets. So we built something
                better.
            </p>
        </div>

        <!-- Mission -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20 items-center">
            <div>
                <h2 class="font-display text-3xl text-white font-bold mb-6">
                    Our <span class="gold-text">Mission</span>
                </h2>
                <p class="text-gray-400 text-base leading-relaxed mb-4">
                    To empower every African event creator — from the small community organizer to the large-scale concert
                    promoter — with the tools they need to create, sell, and manage world-class events.
                </p>
                <p class="text-gray-400 text-base leading-relaxed mb-6">
                    We believe that great events bring people together, celebrate culture, drive economic growth, and create
                    lasting memories. Our job is to make organizing those events as seamless as possible.
                </p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('register') }}"
                        class="btn-gold px-6 py-3 rounded-xl text-black font-semibold text-sm inline-block">
                        Join {{ config('app.name') }}
                    </a>
                    <a href="{{ route('pricing') }}"
                        class="btn-outline-gold px-6 py-3 rounded-xl font-semibold text-sm inline-block">
                        View Pricing
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach ([['number' => '500+', 'label' => 'Events Created', 'icon' => 'fa-calendar'], ['number' => '50K+', 'label' => 'Tickets Sold', 'icon' => 'fa-ticket'], ['number' => '6', 'label' => 'Countries', 'icon' => 'fa-globe'], ['number' => '₦0', 'label' => 'Upfront Cost', 'icon' => 'fa-naira-sign']] as $stat)
                    <div class="glass rounded-2xl p-6 text-center hover:border-amber-400/20 transition-all duration-300">
                        <div class="w-12 h-12 gold-gradient rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid {{ $stat['icon'] }} text-black text-lg"></i>
                        </div>
                        <div class="font-display text-3xl text-white font-bold mb-1">{{ $stat['number'] }}</div>
                        <div class="text-gray-500 text-xs">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Values -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl text-white font-bold">
                    What We <span class="gold-text">Stand For</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([['icon' => 'fa-handshake', 'title' => 'Transparency', 'desc' => 'No hidden fees. No confusing pricing. Just a simple 5% commission on paid tickets.'], ['icon' => 'fa-shield-halved', 'title' => 'Security', 'desc' => 'Your money and your attendees\' data are always protected with enterprise-grade security.'], ['icon' => 'fa-heart', 'title' => 'Community', 'desc' => 'We\'re building more than a platform — we\'re building a community of African event creators.'], ['icon' => 'fa-rocket', 'title' => 'Innovation', 'desc' => 'We\'re constantly improving {{ config('app.name') }} with features that matter to African event organizers.']] as $value)
                    <div
                        class="glass rounded-2xl p-6 text-center hover:border-amber-400/20 transition-all duration-300 group">
                        <div
                            class="w-14 h-14 gold-gradient rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid {{ $value['icon'] }} text-black text-xl"></i>
                        </div>
                        <h3 class="text-white font-semibold mb-2">{{ $value['title'] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Team -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl text-white font-bold">
                    Built with <span class="gold-text">❤️</span> in Nigeria
                </h2>
                <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">
                    {{ config('app.name') }} is proudly built and maintained by a team passionate about African events and technology.
                </p>
            </div>

            {{-- <div class="glass-gold rounded-3xl p-10 text-center relative overflow-hidden">
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-px bg-gradient-to-r from-transparent via-amber-400/50 to-transparent">
                </div>
                <div class="relative z-10">
                    <div class="w-20 h-20 gold-gradient rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-code text-black text-3xl"></i>
                    </div>
                    <h3 class="text-white font-bold text-2xl mb-2">{{ config('app.name') }}</h3>
                    <p class="text-amber-400 text-sm mb-4">Final Year Project — Computer Science</p>
                    <p class="text-gray-400 text-sm max-w-lg mx-auto leading-relaxed">
                        This platform was designed and developed as a final year project, demonstrating the full lifecycle
                        of a modern event management and ticketing system built with Laravel, Tailwind CSS, and Paystack.
                    </p>
                </div>
            </div> --}}
        </div>

        <!-- CTA -->
        <div class="text-center">
            <h2 class="font-display text-3xl text-white font-bold mb-4">
                Ready to Create Your <span class="gold-text">Next Event?</span>
            </h2>
            <p class="text-gray-400 mb-8 max-w-xl mx-auto">
                Join hundreds of event organizers already using {{ config('app.name') }} to sell tickets and grow their audience.
            </p>
            <a href="{{ route('register') }}"
                class="btn-gold px-10 py-4 rounded-2xl text-black font-bold text-lg inline-block">
                Get Started for Free <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

@endsection
