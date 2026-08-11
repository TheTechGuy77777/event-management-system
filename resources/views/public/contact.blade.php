@extends('layouts.public')

@section('title', 'Contact')

@section('content')

    <div x-data="{
        name: '',
        email: '',
        message: '',
        get whatsappUrl() {
            const text = `Hi {{ config('app.name') }}! My name is ${this.name || '(not provided)'}, email: ${this.email || '(not provided)'}.%0A%0A${this.message}`;
            return `https://wa.me/{{ config('services.whatsapp.support_number') }}?text=${encodeURIComponent(text.replace(/%0A/g, '\n'))}`;
        }
    }" class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="text-center mb-10">
            <h1 class="text-white text-3xl lg:text-4xl font-bold mb-3">Contact Us</h1>
            <p class="text-gray-400 text-sm">
                Have a question or need help? Send us a message and we'll get back to you on WhatsApp.
            </p>
        </div>

        <div class="glass rounded-3xl p-8">
            <form @submit.prevent="window.open(whatsappUrl, '_blank')" class="space-y-5">

                <div>
                    <label class="text-gray-400 text-sm font-medium mb-2 block">Full Name</label>
                    <input type="text" x-model="name" placeholder="John Doe" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                </div>

                <div>
                    <label class="text-gray-400 text-sm font-medium mb-2 block">Email Address</label>
                    <input type="email" x-model="email" placeholder="you@example.com" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                </div>

                <div>
                    <label class="text-gray-400 text-sm font-medium mb-2 block">Message</label>
                    <textarea x-model="message" rows="5" placeholder="How can we help?" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all resize-none"></textarea>
                </div>

                <button type="submit"
                    class="btn-gold w-full py-3.5 rounded-xl text-black font-semibold text-sm flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp"></i>
                    Send via WhatsApp
                </button>

                <p class="text-gray-600 text-xs text-center">
                    This opens WhatsApp with your message pre-filled — just hit send there to reach us.
                </p>
            </form>
        </div>
    </div>

@endsection
