@extends('layouts.public')

@section('title', 'Forgot Password')

@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-20">

        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <div class="glass rounded-3xl p-8 lg:p-10 border border-white/5">

                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6 group">
                        <div
                            class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center glow-gold group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-bolt text-black text-sm"></i>
                        </div>
                        <span class="font-display text-2xl">Event<span class="gold-text">Plug</span></span>
                    </a>

                    <!-- Icon -->
                    <div class="w-16 h-16 glass-gold rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-key text-amber-400 text-2xl"></i>
                    </div>

                    <h1 class="text-white text-2xl font-bold mb-2">Forgot your password?</h1>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        No worries. Enter your email and we'll send you a reset link.
                    </p>
                </div>

                <!-- Status -->
                @if (session('status'))
                    <div class="glass-gold rounded-xl p-4 mb-6 text-amber-400 text-sm text-center">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Email Address</label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                                required autofocus
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200 @error('email') border-red-500/50 @enderror">
                        </div>
                        @error('email')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Send Reset Link
                    </button>

                    <a href="{{ route('login') }}"
                        class="btn-outline-gold w-full py-3 rounded-xl font-semibold text-sm text-center block">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Sign In
                    </a>
                </form>
            </div>
        </div>
    </div>

@endsection
