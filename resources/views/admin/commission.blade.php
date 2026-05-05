@extends('layouts.admin')

@section('title', 'Commission — EventPlug')
@section('page-title', 'Commission Settings')
@section('page-subtitle', 'Manage platform commission rates')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Global Commission -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Set Global Rate -->
            <div class="glass rounded-2xl p-6">
                <h2 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-globe text-black text-xs"></i>
                    </div>
                    Global Rate
                </h2>
                <p class="text-gray-500 text-xs mb-5 leading-relaxed">
                    This rate applies to all event managers unless a custom rate is set for them.
                </p>

                <form method="POST" action="{{ route('admin.commission.update') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Commission Rate (%)
                        </label>
                        <div class="relative">
                            <input type="number" name="commission_rate"
                                value="{{ old('commission_rate', $globalRate ?? 5) }}" min="0" max="100"
                                step="0.1" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 pr-10">
                            <span
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-amber-400 font-bold text-sm">%</span>
                        </div>
                        @error('commission_rate')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Example Calculation -->
                    <div class="glass-gold rounded-xl p-4">
                        <p class="text-amber-400 text-xs font-medium mb-2">
                            <i class="fa-solid fa-calculator mr-1"></i>Example
                        </p>
                        <p class="text-gray-400 text-xs leading-relaxed">
                            For a ₦5,000 ticket at <span class="text-amber-400" id="rate-display">5</span>%:
                        </p>
                        <div class="mt-2 space-y-1">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Guest pays:</span>
                                <span class="text-white" id="guest-pays">₦5,250</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Manager earns:</span>
                                <span class="text-green-400" id="manager-earns">₦5,000</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Platform earns:</span>
                                <span class="text-amber-400" id="platform-earns">₦250</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Save Global Rate
                    </button>
                </form>
            </div>

            <!-- Info Card -->
            <div class="glass rounded-2xl p-6">
                <h3 class="text-white font-semibold text-sm mb-4">
                    <i class="fa-solid fa-circle-info text-amber-400 mr-2"></i>How It Works
                </h3>
                <ul class="space-y-3 text-gray-500 text-xs leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        The global rate applies to all new events published after the change.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        Already published events keep their locked-in rate forever.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        You can set a custom rate per event manager to override the global rate.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Per Manager Rates -->
        <div class="lg:col-span-2">
            <div class="glass rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5">
                    <h2 class="text-white font-semibold">Per Manager Custom Rates</h2>
                    <p class="text-gray-500 text-xs mt-1">Override the global rate for specific event managers</p>
                </div>

                <div class="divide-y divide-white/5">
                    @forelse($managers ?? [] as $manager)
                        <div class="px-6 py-4 hover:bg-white/2 transition-colors">
                            <div class="flex items-center gap-4">

                                <!-- Manager Info -->
                                <div
                                    class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
                                    <span class="text-black font-bold text-sm">
                                        {{ strtoupper(substr($manager->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-medium">{{ $manager->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $manager->email }}</p>
                                </div>

                                <!-- Custom Rate Form -->
                                <form method="POST" action="{{ route('admin.commission.update') }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="manager_id" value="{{ $manager->id }}">
                                    <div class="relative">
                                        <input type="number" name="commission_rate"
                                            value="{{ $manager->custom_commission ?? '' }}"
                                            placeholder="{{ $globalRate ?? 5 }}" min="0" max="100"
                                            step="0.1"
                                            class="w-24 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all pr-7">
                                        <span
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-amber-400 text-xs">%</span>
                                    </div>
                                    <button type="submit"
                                        class="btn-gold px-3 py-2 rounded-xl text-black font-semibold text-xs">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-14 h-14 glass rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-users text-amber-400/30 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">No event managers registered yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Live commission calculator
        const rateInput = document.querySelector('input[name="commission_rate"]');
        if (rateInput) {
            rateInput.addEventListener('input', function() {
                const rate = parseFloat(this.value) || 0;
                const ticketPrice = 5000;
                const commission = ticketPrice * (rate / 100);
                const guestPays = ticketPrice + commission;

                document.getElementById('rate-display').textContent = rate;
                document.getElementById('guest-pays').textContent = '₦' + guestPays.toLocaleString();
                document.getElementById('manager-earns').textContent = '₦' + ticketPrice.toLocaleString();
                document.getElementById('platform-earns').textContent = '₦' + commission.toLocaleString();
            });
        }
    </script>
@endpush
