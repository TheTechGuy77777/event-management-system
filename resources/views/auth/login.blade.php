@extends('layouts.public')

@section('title', 'Sign In')

@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-20">

        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">

            <!-- Card -->
            <div class="glass rounded-3xl p-8 lg:p-10 border border-white/5">

                <!-- Header -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6 group">
                        <div
                            class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center glow-gold group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-bolt text-black text-sm"></i>
                        </div>
                        <span class="font-display text-2xl">Event<span class="gold-text">Plug</span></span>
                    </a>
                    <h1 class="text-white text-2xl font-bold mb-2">Welcome back</h1>
                    <p class="text-gray-500 text-sm">Sign in to your {{ config('app.name') }} account</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="glass-gold rounded-xl p-4 mb-6 text-amber-400 text-sm text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    @if (session('success'))
                        <div class="mb-5 rounded-xl bg-green-500/10 border border-green-500/20 p-4 text-green-400 text-sm">
                            <i class="fa-solid fa-circle-check mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 rounded-xl bg-red-500/10 border border-red-500/20 p-4 text-red-400 text-sm">
                            <i class="fa-solid fa-circle-exclamation mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

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

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-gray-400 text-sm font-medium">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-amber-400 text-xs hover:text-amber-300 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" required
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

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 rounded border border-white/20 bg-white/5 text-amber-400 focus:ring-amber-400/30 cursor-pointer">
                        <label for="remember" class="text-gray-400 text-sm cursor-pointer">Remember me for 30 days</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm mt-2">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>Sign In
                    </button>

                    <!-- Divider -->
                    <div class="relative flex items-center gap-4 py-2">
                        <div class="flex-1 h-px bg-white/5"></div>
                        <span class="text-gray-600 text-xs">Don't have an account?</span>
                        <div class="flex-1 h-px bg-white/5"></div>
                    </div>

                    <!-- Register Link -->
                    <a href="{{ route('register') }}"
                        class="btn-outline-gold w-full py-3 rounded-xl font-semibold text-sm text-center block">
                        Create Your Account
                    </a>

                </form>
            </div>

            <!-- Footer note -->
            <p class="text-center text-gray-600 text-xs mt-6">
                By signing in, you agree to our
                <a href="#" class="text-amber-400/70 hover:text-amber-400 transition-colors">Terms</a>
                and
                <a href="#" class="text-amber-400/70 hover:text-amber-400 transition-colors">Privacy Policy</a>
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
