@extends('layouts.public')

@section('title', 'Pricing')

@section('content')

    <!-- Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <!-- Header -->
        <div class="text-center mb-16">
            <div
                class="inline-flex items-center gap-2 glass-gold px-4 py-2 rounded-full text-amber-400 text-sm font-medium mb-6">
                <i class="fa-solid fa-tag text-xs"></i>
                Simple & Transparent
            </div>
            <h1 class="font-display text-4xl lg:text-6xl text-white font-bold mb-4">
                No Upfront Costs.<br>
                <span class="gold-text">Ever.</span>
            </h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ config('app.name') }} works on a simple commission model. You only pay when you sell tickets. No monthly
                fees, no setup
                costs, no hidden charges.
            </p>
        </div>

        <!-- Main Pricing Card -->
        <div class="max-w-3xl mx-auto mb-16">
            <div class="glass-gold rounded-3xl p-10 text-center relative overflow-hidden">
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-px bg-gradient-to-r from-transparent via-amber-400/50 to-transparent">
                </div>

                <div class="w-20 h-20 gold-gradient rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-percent text-black text-3xl"></i>
                </div>

                <h2 class="text-white text-4xl font-bold mb-2">5% per ticket</h2>
                <p class="text-amber-400 text-lg mb-8">Platform commission on each ticket sold</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div class="glass rounded-2xl p-5 text-left">
                        <p class="text-amber-400 text-xs font-medium uppercase tracking-wider mb-3">
                            Attendee Pays Model
                        </p>
                        <p class="text-white text-sm leading-relaxed">
                            The 5% commission is added on top of the ticket price. Your attendees pay slightly more, but you
                            receive your full ticket value.
                        </p>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Ticket Price</span>
                                <span class="text-white">₦10,000</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Platform Fee (5%)</span>
                                <span class="text-gray-400">₦500</span>
                            </div>
                            <div class="flex justify-between text-xs border-t border-white/5 pt-2">
                                <span class="text-gray-500">Attendee Pays</span>
                                <span class="text-amber-400 font-bold">₦10,500</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">You Receive</span>
                                <span class="text-green-400 font-bold">₦10,000</span>
                            </div>
                        </div>
                    </div>

                    <div class="glass rounded-2xl p-5 text-left">
                        <p class="text-amber-400 text-xs font-medium uppercase tracking-wider mb-3">
                            You Pay Later Model
                        </p>
                        <p class="text-white text-sm leading-relaxed">
                            The 5% commission is deducted from your payout. Your attendees pay the exact ticket price you
                            set.
                        </p>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Ticket Price</span>
                                <span class="text-white">₦10,000</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Platform Fee (5%)</span>
                                <span class="text-gray-400">₦500</span>
                            </div>
                            <div class="flex justify-between text-xs border-t border-white/5 pt-2">
                                <span class="text-gray-500">Attendee Pays</span>
                                <span class="text-amber-400 font-bold">₦10,000</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">You Receive</span>
                                <span class="text-green-400 font-bold">₦9,500</span>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('register') }}"
                    class="btn-gold px-10 py-4 rounded-2xl text-black font-bold text-lg inline-block">
                    Get Started for Free <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Features -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            @foreach ([['icon' => 'fa-circle-check', 'title' => 'No Monthly Fees', 'desc' => 'Zero subscription costs. Create your account and start selling immediately with no upfront payment.'], ['icon' => 'fa-shield-halved', 'title' => 'Secure Payments', 'desc' => 'All payments processed securely via Paystack and Monnify. Your money is always safe.'], ['icon' => 'fa-bolt', 'title' => 'Instant Payouts', 'desc' => 'Receive your earnings directly to your registered bank account after each event ends.'], ['icon' => 'fa-ticket', 'title' => 'Unlimited Tickets', 'desc' => 'Create as many ticket types as you need. Free, paid, VIP, group — all supported.'], ['icon' => 'fa-chart-line', 'title' => 'Real-time Analytics', 'desc' => 'Track sales, page views, conversion rates, and revenue from your dashboard.'], ['icon' => 'fa-headset', 'title' => 'Dedicated Support', 'desc' => 'Our team is always ready to help you set up and run successful events.']] as $feature)
                <div class="glass rounded-2xl p-6 hover:border-amber-400/20 transition-all duration-300 group">
                    <div
                        class="w-12 h-12 gold-gradient rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid {{ $feature['icon'] }} text-black text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- FAQ -->
        <div class="max-w-3xl mx-auto">
            <h2 class="font-display text-3xl text-white font-bold text-center mb-8">
                Frequently Asked <span class="gold-text">Questions</span>
            </h2>

            <div class="space-y-4" x-data="{ open: null }">
                @foreach ([['q' => 'When do I receive my payout?', 'a' => 'Payouts are processed after your event ends. The platform commission is deducted and the remaining balance is transferred to your registered bank account within 2-3 business days.'], ['q' => 'Can I create free events?', 'a' => 'Yes! You can create free events and free ticket types at no cost. The 5% commission only applies to paid tickets.'], ['q' => 'Is there a limit on how many events I can create?', 'a' => 'No limit! Create as many events as you need. ' . config('app.name') . ' grows with your business.'], ['q' => 'What payment methods do attendees use?', 'a' => 'We support card payments, bank transfers, and USSD via Paystack and Monnify — Nigeria\'s most trusted payment gateways.'], ['q' => 'Can I offer discount codes?', 'a' => 'Yes! You can create promo codes with percentage or fixed discounts, set usage limits, and expiry dates from your dashboard.']] as $index => $faq)
                    <div class="glass rounded-2xl overflow-hidden">
                        <button class="w-full flex items-center justify-between px-6 py-5 text-left"
                            x-on:click="open = open === {{ $index }} ? null : {{ $index }}">
                            <span class="text-white font-medium text-sm">{{ $faq['q'] }}</span>
                            <i class="fa-solid fa-chevron-down text-amber-400 text-xs transition-transform duration-300"
                                :class="open === {{ $index }} ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === {{ $index }}" x-transition class="px-6 pb-5">
                            <p class="text-gray-400 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
