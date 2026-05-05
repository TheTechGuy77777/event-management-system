@extends('layouts.public')

@section('title', 'Verify Email — EventPlug')

@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-20">

        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <div class="glass rounded-3xl p-8 lg:p-10 border border-white/5 text-center">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-8 group">
                    <div
                        class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center glow-gold group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-bolt text-black text-sm"></i>
                    </div>
                    <span class="font-display text-2xl">Event<span class="gold-text">Plug</span></span>
                </a>

                <!-- Icon -->
                <div class="w-20 h-20 glass-gold rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-envelope-open-text text-amber-400 text-3xl"></i>
                </div>

                <!-- Text -->
                <h1 class="text-white text-2xl font-bold mb-3">Check your inbox</h1>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    We sent a verification link to your email address. Click the link to activate your account and start
                    creating events.
                </p>

                <!-- Status -->
                @if (session('status') == 'verification-link-sent')
                    <div class="glass-gold rounded-xl p-4 mb-6 text-amber-400 text-sm">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        A new verification link has been sent to your email.
                    </div>
                @endif

                <!-- Resend -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Resend Verification Email
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline-gold w-full py-3 rounded-xl font-semibold text-sm">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Sign Out
                    </button>
                </form>

            </div>

            <p class="text-center text-gray-600 text-xs mt-6">
                Didn't receive the email? Check your spam folder or resend above.
            </p>
        </div>
    </div>

@endsection
