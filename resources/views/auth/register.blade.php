@extends('layouts.public')

@section('title', 'Create Account — EventPlug')

@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-20">

        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg">

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
                    <h1 class="text-white text-2xl font-bold mb-2">Create your account</h1>
                    <p class="text-gray-500 text-sm">Start selling tickets in minutes — no setup fees</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Full Name <span
                                class="text-amber-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required
                                autofocus
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200 @error('name') border-red-500/50 @enderror">
                        </div>
                        @error('name')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Email Address <span
                                class="text-amber-400">*</span></label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                                required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200 @error('email') border-red-500/50 @enderror">
                        </div>
                        @error('email')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Phone Number <span
                                class="text-amber-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+234 800 000 0000"
                                required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200 @error('phone') border-red-500/50 @enderror">
                        </div>
                        @error('phone')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Organization -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Organization / Brand Name
                            <span class="text-gray-600 font-normal ml-1">(optional)</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" name="organization_name" value="{{ old('organization_name') }}"
                                placeholder="Your company or brand name"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Password <span
                                class="text-amber-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" required
                                oninput="checkStrength(this.value)"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200 @error('password') border-red-500/50 @enderror">
                            <button type="button" onclick="togglePassword('password', 'eye1')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-amber-400 transition-colors">
                                <i class="fa-solid fa-eye text-sm" id="eye1"></i>
                            </button>
                        </div>

                        <!-- Password Strength -->
                        <div class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300" id="str1">
                                </div>
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300" id="str2">
                                </div>
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300" id="str3">
                                </div>
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300" id="str4">
                                </div>
                            </div>
                            <p class="text-xs text-gray-600" id="strength-label">Min. 8 chars with uppercase, lowercase,
                                number & symbol</p>
                        </div>

                        @error('password')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Confirm Password <span
                                class="text-amber-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="••••••••" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 focus:bg-white/8 transition-all duration-200">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-amber-400 transition-colors">
                                <i class="fa-solid fa-eye text-sm" id="eye2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm mt-2">
                        <i class="fa-solid fa-rocket mr-2"></i>Create My Account
                    </button>

                    <!-- Divider -->
                    <div class="relative flex items-center gap-4 py-2">
                        <div class="flex-1 h-px bg-white/5"></div>
                        <span class="text-gray-600 text-xs">Already have an account?</span>
                        <div class="flex-1 h-px bg-white/5"></div>
                    </div>

                    <!-- Login Link -->
                    <a href="{{ route('login') }}"
                        class="btn-outline-gold w-full py-3 rounded-xl font-semibold text-sm text-center block">
                        Sign In Instead
                    </a>

                </form>
            </div>

            <p class="text-center text-gray-600 text-xs mt-6">
                By creating an account, you agree to our
                <a href="#" class="text-amber-400/70 hover:text-amber-400 transition-colors">Terms</a>
                and
                <a href="#" class="text-amber-400/70 hover:text-amber-400 transition-colors">Privacy Policy</a>
            </p>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function checkStrength(value) {
            const bars = ['str1', 'str2', 'str3', 'str4'];
            const label = document.getElementById('strength-label');

            let score = 0;
            if (value.length >= 8) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            const colors = {
                0: 'bg-white/10',
                1: 'bg-red-500',
                2: 'bg-orange-500',
                3: 'bg-yellow-500',
                4: 'bg-green-500',
            };

            const labels = {
                0: 'Min. 8 chars with uppercase, lowercase, number & symbol',
                1: 'Weak — keep going',
                2: 'Fair — getting better',
                3: 'Strong — almost there',
                4: 'Very Strong ✓',
            };

            const labelColors = {
                0: 'text-gray-600',
                1: 'text-red-400',
                2: 'text-orange-400',
                3: 'text-yellow-400',
                4: 'text-green-400',
            };

            bars.forEach((id, index) => {
                const bar = document.getElementById(id);
                bar.className = 'h-1 flex-1 rounded-full transition-all duration-300 ';
                bar.className += index < score ? colors[score] : 'bg-white/10';
            });

            label.textContent = labels[score];
            label.className = 'text-xs transition-colors duration-300 ' + labelColors[score];
        }
    </script>
@endpush
