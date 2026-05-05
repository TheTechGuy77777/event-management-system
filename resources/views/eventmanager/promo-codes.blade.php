@extends('layouts.dashboard')

@section('title', 'Promo Codes — EventPlug')
@section('page-title', 'Promo Codes')
@section('page-subtitle', 'Create and manage discount codes for your events')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Create Promo Code -->
        <div class="lg:col-span-1">
            <div class="glass rounded-2xl p-6 sticky top-24">
                <h2 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-tag text-black text-xs"></i>
                    </div>
                    New Promo Code
                </h2>
                <p class="text-gray-500 text-xs mb-5 leading-relaxed">
                    Create discount codes for your events. Share them with your audience to boost ticket sales.
                </p>

                <form method="POST" action="{{ route('dashboard.promo-codes.store') }}" class="space-y-4">
                    @csrf

                    <!-- Event -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Event <span class="text-amber-400">*</span>
                        </label>
                        <select name="event_id" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                            <option value="">Select event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                    ({{ ucfirst($event->status) }})
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Code
                            <span class="text-gray-600 font-normal ml-1">(auto-generated if empty)</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. EARLY20"
                            maxlength="20" oninput="this.value = this.value.toUpperCase()"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all font-mono @error('code') border-red-500/50 @enderror">
                        @error('code')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Type & Value -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Type <span class="text-amber-400">*</span>
                            </label>
                            <select name="discount_type" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)
                                </option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                    Fixed (₦)
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Value <span class="text-amber-400">*</span>
                            </label>
                            <input type="number" name="discount_value" value="{{ old('discount_value') }}"
                                placeholder="20" min="1" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all @error('discount_value') border-red-500/50 @enderror">
                            @error('discount_value')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Usage Limit -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Usage Limit
                            <span class="text-gray-600 font-normal ml-1">(unlimited if empty)</span>
                        </label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="e.g. 50"
                            min="1"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Expiry Date
                            <span class="text-gray-600 font-normal ml-1">(optional)</span>
                        </label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                    </div>

                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-plus mr-2"></i>Create Promo Code
                    </button>
                </form>
            </div>
        </div>

        <!-- Promo Codes List -->
        <div class="lg:col-span-2">
            <div class="glass rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-white font-semibold">Your Promo Codes</h2>
                    <span class="text-gray-500 text-sm">{{ $promoCodes->total() }} total</span>
                </div>

                @if ($promoCodes->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach ($promoCodes as $promo)
                            <div class="px-6 py-5 hover:bg-white/2 transition-colors">
                                <div class="flex items-start justify-between gap-4">

                                    <!-- Code Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="font-mono font-bold text-amber-400 text-lg tracking-wider">
                                                {{ $promo->code }}
                                            </span>
                                            @if ($promo->isValid())
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full text-green-400 bg-green-500/10 border border-green-500/20">
                                                    Active
                                                </span>
                                            @else
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full text-gray-500 bg-white/5 border border-white/10">
                                                    Inactive
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-400 text-sm mb-1">
                                            <i class="fa-solid fa-calendar text-amber-400/50 mr-1"></i>
                                            {{ $promo->event->name ?? '—' }}
                                        </p>

                                        <div class="flex items-center gap-4 mt-2">
                                            <!-- Discount -->
                                            <div class="glass px-3 py-1 rounded-lg">
                                                <span class="text-white text-xs font-semibold">
                                                    @if ($promo->discount_type === 'percentage')
                                                        {{ $promo->discount_value }}% OFF
                                                    @else
                                                        ₦{{ number_format($promo->discount_value) }} OFF
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Usage -->
                                            <span class="text-gray-500 text-xs">
                                                <i class="fa-solid fa-users mr-1"></i>
                                                {{ $promo->usage_count }}
                                                @if ($promo->usage_limit)
                                                    / {{ $promo->usage_limit }} used
                                                @else
                                                    used (unlimited)
                                                @endif
                                            </span>

                                            <!-- Expiry -->
                                            @if ($promo->expires_at)
                                                <span class="text-gray-500 text-xs">
                                                    <i class="fa-solid fa-clock mr-1"></i>
                                                    Expires {{ $promo->expires_at->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 flex-shrink-0">

                                        <!-- Copy Code -->
                                        <button onclick="copyCode('{{ $promo->code }}')"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                            title="Copy Code">
                                            <i class="fa-solid fa-copy text-xs"></i>
                                        </button>

                                        <!-- Toggle Active -->
                                        <form method="POST" action="{{ route('dashboard.promo-codes.toggle', $promo) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 glass rounded-lg flex items-center justify-center transition-colors
                                            {{ $promo->is_active ? 'text-gray-400 hover:text-yellow-400' : 'text-gray-400 hover:text-green-400' }}"
                                                title="{{ $promo->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i
                                                    class="fa-solid {{ $promo->is_active ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('dashboard.promo-codes.destroy', $promo) }}"
                                            onsubmit="return confirm('Delete promo code {{ $promo->code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-red-400 transition-colors"
                                                title="Delete">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($promoCodes->hasPages())
                        <div class="px-6 py-4 border-t border-white/5">
                            {{ $promoCodes->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-tag text-amber-400/30 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm mb-2">No promo codes yet.</p>
                        <p class="text-gray-600 text-xs">Create your first promo code to boost ticket sales.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Copy Success Toast -->
    <div id="copy-toast"
        class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 bg-[#1a1a1a] border border-green-500/30 text-green-400 px-5 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-circle-check"></i>
        <p class="text-sm font-medium">Code copied to clipboard!</p>
    </div>

@endsection

@push('scripts')
    <script>
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const toast = document.getElementById('copy-toast');
                toast.classList.remove('hidden');
                toast.classList.add('flex');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex');
                }, 3000);
            });
        }
    </script>
@endpush
