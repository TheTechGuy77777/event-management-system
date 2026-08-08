@extends('layouts.dashboard')

@section('title', 'Profile')
@section('page-title', 'Profile & Settings')
@section('page-subtitle', 'Manage your personal information')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Profile Photo & Info -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Photo Card -->
            <div class="glass rounded-2xl p-6 text-center">
                <div class="relative inline-block mb-4">
                    <div
                        class="w-24 h-24 rounded-2xl gold-gradient flex items-center justify-center mx-auto overflow-hidden">
                        @if (auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                class="w-full h-full object-cover" id="photo-preview">
                        @else
                            <span class="text-black font-bold text-3xl" id="photo-initial">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <label for="photo-input"
                        class="absolute -bottom-2 -right-2 w-8 h-8 gold-gradient rounded-xl flex items-center justify-center cursor-pointer hover:opacity-90 transition-opacity">
                        <i class="fa-solid fa-camera text-black text-xs"></i>
                    </label>
                </div>

                <h3 class="text-white font-semibold text-lg">{{ auth()->user()->name }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->email }}</p>

                @if (auth()->user()->organization_name)
                    <div class="glass-gold rounded-xl px-3 py-1.5 inline-flex items-center gap-2 mt-3">
                        <i class="fa-solid fa-building text-amber-400 text-xs"></i>
                        <span class="text-amber-400 text-xs">{{ auth()->user()->organization_name }}</span>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-t border-white/5 space-y-2 text-left">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Member since</span>
                        <span class="text-gray-300">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Total Events</span>
                        <span class="text-gray-300">{{ auth()->user()->events->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Email Status</span>
                        @if (auth()->user()->email_verified_at)
                            <span class="text-green-400 text-xs">Verified</span>
                        @else
                            <span class="text-red-400 text-xs">Unverified</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="glass rounded-2xl p-6">
                <h3 class="text-white font-semibold text-sm mb-4">Quick Links</h3>
                <div class="space-y-2">
                    <a href="{{ route('dashboard.account') }}"
                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/4 transition-colors group">
                        <div class="w-8 h-8 glass rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-building-columns text-amber-400/60 text-xs"></i>
                        </div>
                        <span class="text-gray-400 text-sm group-hover:text-white transition-colors">Bank Account</span>
                        <i class="fa-solid fa-chevron-right text-gray-600 text-xs ml-auto"></i>
                    </a>
                    <a href="{{ route('dashboard.notifications') }}"
                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/4 transition-colors group">
                        <div class="w-8 h-8 glass rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-bell text-amber-400/60 text-xs"></i>
                        </div>
                        <span class="text-gray-400 text-sm group-hover:text-white transition-colors">Notifications</span>
                        <i class="fa-solid fa-chevron-right text-gray-600 text-xs ml-auto"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Update Profile -->
            <div class="glass rounded-2xl p-6">
                <h2 class="text-white font-semibold mb-6 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user text-black text-xs"></i>
                    </div>
                    Personal Information
                </h2>

                <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <!-- Hidden photo input -->
                    <input type="file" id="photo-input" name="profile_photo" accept="image/jpeg,image/png" class="hidden"
                        onchange="previewPhoto(this)">

                    <!-- Name -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Full Name <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('name') border-red-500/50 @enderror">
                        </div>
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Phone Number <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('phone') border-red-500/50 @enderror">
                        </div>
                        @error('phone')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
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
                            <input type="text" name="organization_name"
                                value="{{ old('organization_name', auth()->user()->organization_name) }}"
                                placeholder="Your company or brand name"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Email (readonly) -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Email Address
                            <span class="text-gray-600 font-normal ml-1">(cannot be changed)</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-sm"></i>
                            <input type="email" value="{{ auth()->user()->email }}" readonly
                                class="w-full bg-white/3 border border-white/5 rounded-xl pl-11 pr-4 py-3 text-gray-600 text-sm cursor-not-allowed">
                        </div>
                        <p class="text-gray-600 text-xs mt-1">
                            Contact support to change your email address.
                        </p>
                    </div>

                    <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="glass rounded-2xl p-6">
                <h2 class="text-white font-semibold mb-6 flex items-center gap-2">
                    <div
                        class="w-7 h-7 bg-purple-500/20 border border-purple-500/30 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-lock text-purple-400 text-xs"></i>
                    </div>
                    Change Password
                </h2>

                <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="change_password" value="1">

                    <!-- Current Password -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Current Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="current_password" id="current_password" placeholder="••••••••"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('current_password') border-red-500/50 @enderror">
                            <button type="button" onclick="togglePass('current_password', 'eye0')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-amber-400 transition-colors">
                                <i class="fa-solid fa-eye text-sm" id="eye0"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">New Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="password" id="new_password" placeholder="••••••••"
                                oninput="checkStrength(this.value)"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('password') border-red-500/50 @enderror">
                            <button type="button" onclick="togglePass('new_password', 'eye1')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-amber-400 transition-colors">
                                <i class="fa-solid fa-eye text-sm" id="eye1"></i>
                            </button>
                        </div>

                        <!-- Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300"
                                    id="str1"></div>
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300"
                                    id="str2"></div>
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300"
                                    id="str3"></div>
                                <div class="h-1 flex-1 rounded-full bg-white/10 transition-all duration-300"
                                    id="str4"></div>
                            </div>
                            <p class="text-xs text-gray-600" id="strength-label">
                                Min. 8 chars with uppercase, lowercase, number & symbol
                            </p>
                        </div>

                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Confirm New Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" name="password_confirmation" id="confirm_password"
                                placeholder="••••••••"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                            <button type="button" onclick="togglePass('confirm_password', 'eye2')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-amber-400 transition-colors">
                                <i class="fa-solid fa-eye text-sm" id="eye2"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-outline-gold px-8 py-3 rounded-xl font-semibold text-sm">
                        <i class="fa-solid fa-lock mr-2"></i>Update Password
                    </button>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="glass rounded-2xl p-6 border border-red-500/10">
                <h2 class="text-red-400 font-semibold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Danger Zone
                </h2>
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    Once you delete your account, all your events and data will be permanently removed. This action cannot
                    be undone.
                </p>
                <form method="POST" action="{{ route('dashboard.profile.destroy') }}"
                    onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-500/20 transition-colors">
                        <i class="fa-solid fa-trash mr-2"></i>Delete My Account
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photo-preview');
                    const initial = document.getElementById('photo-initial');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        // Create img element if not exists
                        const container = input.previousElementSibling;
                        container.innerHTML = '<img src="' + e.target.result +
                            '" id="photo-preview" class="w-full h-full object-cover">';
                    }
                    if (initial) initial.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePass(fieldId, iconId) {
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
                4: 'bg-green-500'
            };
            const labels = {
                0: 'Min. 8 chars with uppercase, lowercase, number & symbol',
                1: 'Weak — keep going',
                2: 'Fair — getting better',
                3: 'Strong — almost there',
                4: 'Very Strong ✓'
            };
            const labelColors = {
                0: 'text-gray-600',
                1: 'text-red-400',
                2: 'text-orange-400',
                3: 'text-yellow-400',
                4: 'text-green-400'
            };

            bars.forEach((id, index) => {
                const bar = document.getElementById(id);
                bar.className = 'h-1 flex-1 rounded-full transition-all duration-300 ' +
                    (index < score ? colors[score] : 'bg-white/10');
            });

            label.textContent = labels[score];
            label.className = 'text-xs transition-colors duration-300 ' + labelColors[score];
        }
    </script>
@endpush
