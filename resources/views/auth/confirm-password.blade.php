@extends('layouts.public')

@section('title', 'Confirm Password')

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
                        <span class="font-display text-2xl">Chibuzo<span class="gold-text">Connect</span></span>
                    </a>

                    <!-- Icon -->
                    <div class="w-16 h-16 glass-gold rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-shield-halved text-amber-400 text-2xl"></i>
                    </div>

                    <h1 class="text-white text-2xl font-bold mb-2">Confirm your password</h1>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        This is a secure area. Please confirm your password before continuing.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                    @csrf

                    <!-- Password -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" required autofocus
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200 @error('password') border-red-500/50 @enderror">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-amber-400 transition-colors">
                                <i class="fa-solid fa-eye text-sm" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-shield-check mr-2"></i>Confirm Password
                    </button>

                    <a href="{{ route('login') }}"
                        class="btn-outline-gold w-full py-3 rounded-xl font-semibold text-sm text-center block">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Sign In
                    </a>
                </form>
            </div>

            <p class="text-center text-gray-600 text-xs mt-6">
                For your security, you'll be asked to confirm your password periodically.
            </p>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
@endpush
