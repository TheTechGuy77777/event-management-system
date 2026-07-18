@extends('layouts.public')

@section('title', 'Checkout — ' . $event->name)

@section('content')

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-20px]"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-20px]"
            class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-[#1a1a1a] border border-red-500/30 text-red-400 px-5 py-4 rounded-2xl shadow-2xl max-w-sm">
            <i class="fa-solid fa-circle-exclamation text-lg flex-shrink-0"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
            <button x-on:click="show = false"
                class="ml-2 text-red-400/50 hover:text-red-400 transition-colors flex-shrink-0">
                <i class="fa-solid fa-times text-xs"></i>
            </button>
        </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Back -->
        <a href="/events/{{ $event->slug }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm mb-8">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to Event
        </a>

        <!-- Event Header -->
        <div class="glass rounded-2xl p-6 mb-8 flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                @if ($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-amber-400/30 text-2xl"></i>
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-white font-bold text-xl">{{ $event->name }}</h1>
                <p class="text-gray-400 text-sm mt-1">
                    <i class="fa-solid fa-calendar text-amber-400/60 mr-2"></i>
                    {{ $event->start_date?->format('D, d M Y • h:i A') }}
                </p>
                <p class="text-gray-400 text-sm mt-0.5">
                    <i class="fa-solid fa-location-dot text-amber-400/60 mr-2"></i>
                    {{ $event->is_virtual ? 'Virtual Event' : $event->location }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.store', $event->slug) }}" x-data="checkout()"
            class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Step 1 — Select Ticket -->
                <div class="glass rounded-2xl p-6">
                    <h2 class="text-white font-semibold text-lg mb-5 flex items-center gap-2">
                        <div
                            class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center text-black text-xs font-bold">
                            1</div>
                        Select Ticket
                    </h2>

                    <div class="space-y-3">
                        @foreach ($event->tickets->where('is_active', true) as $ticket)
                            @if ($ticket->ticket_type !== 'invite_only')
                                <label class="cursor-pointer block">
                                    <input type="radio" name="ticket_id" value="{{ $ticket->id }}"
                                        x-model="selectedTicketId"
                                        x-on:change="updateTicket({{ $ticket->price }}, {{ $ticket->purchase_limit }}, '{{ $ticket->name }}', {{ $ticket->remainingQuantity() }})"
                                        {{ ($selectedTicket && $selectedTicket->id == $ticket->id) || (!$selectedTicket && $loop->first) ? 'checked' : '' }}
                                        {{ $ticket->isSoldOut() ? 'disabled' : '' }} class="hidden peer">
                                    <div
                                        class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200
                            {{ $ticket->isSoldOut() ? 'opacity-50 cursor-not-allowed' : 'hover:border-white/20' }}">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-white text-sm font-semibold">{{ $ticket->name }}</p>
                                                @if ($ticket->description)
                                                    <p class="text-gray-500 text-xs mt-0.5">{{ $ticket->description }}</p>
                                                @endif
                                                @if ($ticket->perks->count() > 0)
                                                    <ul class="mt-2 space-y-1">
                                                        @foreach ($ticket->perks as $perk)
                                                            <li class="flex items-center gap-1.5 text-gray-500 text-xs">
                                                                <i class="fa-solid fa-check text-amber-400/60 text-xs"></i>
                                                                {{ $perk->perk }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="text-right flex-shrink-0 ml-4">
                                                @if ($ticket->isSoldOut())
                                                    <span class="text-red-400 text-sm font-bold">Sold Out</span>
                                                @elseif($ticket->ticket_type === 'free')
                                                    <span class="text-green-400 text-sm font-bold">Free</span>
                                                @else
                                                    <span class="text-amber-400 text-sm font-bold">
                                                        ₦{{ number_format($ticket->price) }}
                                                    </span>
                                                @endif
                                                <p class="text-gray-600 text-xs mt-1">
                                                    {{ $ticket->remainingQuantity() }} left
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>

                    <!-- Quantity -->
                    <div class="mt-5">
                        <label class="text-gray-400 text-sm font-medium mb-3 block">Quantity</label>
                        <div class="flex items-center gap-4">
                            <button type="button" x-on:click="quantity > 1 ? quantity-- : null"
                                class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors text-lg font-bold">
                                −
                            </button>
                            <span class="text-white font-bold text-xl w-8 text-center" x-text="quantity"></span>
                            <button type="button" x-on:click="quantity < maxQuantity ? quantity++ : null"
                                class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors text-lg font-bold">
                                +
                            </button>
                            <input type="hidden" name="quantity" :value="quantity">
                            <span class="text-gray-500 text-xs">Max <span x-text="maxQuantity"></span> per order</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2 — Attendee Info -->
                <div class="glass rounded-2xl p-6">
                    <h2 class="text-white font-semibold text-lg mb-5 flex items-center gap-2">
                        <div
                            class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center text-black text-xs font-bold">
                            2</div>
                        Your Details
                    </h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-gray-400 text-sm font-medium mb-2 block">
                                    Full Name <span class="text-amber-400">*</span>
                                </label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                                    <input type="text" name="buyer_name" value="{{ old('buyer_name') }}"
                                        placeholder="John Doe" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all @error('buyer_name') border-red-500/50 @enderror">
                                </div>
                                @error('buyer_name')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm font-medium mb-2 block">
                                    Phone Number
                                </label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                                    <input type="tel" name="buyer_phone" value="{{ old('buyer_phone') }}"
                                        placeholder="+234 800 000 0000"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Email Address <span class="text-amber-400">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                                <input type="email" name="buyer_email" value="{{ old('buyer_email') }}"
                                    placeholder="your@email.com" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all @error('buyer_email') border-red-500/50 @enderror">
                            </div>
                            @error('buyer_email')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buying for others -->
                        <div class="glass-gold rounded-xl p-4">
                            <div class="flex items-center gap-3 cursor-pointer"
                                x-on:click="buyingForOthers = !buyingForOthers">
                                <div class="w-8 h-5 rounded-full transition-colors duration-300 relative flex-shrink-0"
                                    :class="buyingForOthers ? 'bg-amber-400' : 'bg-white/10'">
                                    <div class="w-3 h-3 bg-white rounded-full absolute top-1 transition-transform duration-300"
                                        :class="buyingForOthers ? 'translate-x-4' : 'translate-x-1'"></div>
                                </div>
                                <p class="text-amber-400 text-sm font-medium">
                                    I'm buying tickets for other people too
                                </p>
                            </div>
                        </div>

                        <!-- Attendee Details for others -->
                        <div x-show="buyingForOthers && quantity > 1" x-transition>
                            <p class="text-gray-400 text-sm mb-3">
                                Fill in details for each ticket (optional):
                            </p>
                            <div class="space-y-3">
                                <template x-for="i in quantity - 1" :key="i">
                                    <div class="glass rounded-xl p-4">
                                        <p class="text-amber-400 text-xs font-medium mb-3"
                                            x-text="'Ticket ' + (i + 1) + ' — Other Attendee'"></p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <input type="text" :name="'attendees[' + i + '][name]'"
                                                placeholder="Attendee name (optional)"
                                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                            <input type="email" :name="'attendees[' + i + '][email]'"
                                                placeholder="Attendee email (optional)"
                                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Notice -->
                        <div class="glass rounded-xl p-4 flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-amber-400 mt-0.5 flex-shrink-0"></i>
                            <p class="text-gray-400 text-xs leading-relaxed">
                                A unique ticket code will be sent to the provided email address. You will need to present
                                this code at the event entrance to gain access.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 — Payment -->
                <div class="glass rounded-2xl p-6">
                    <h2 class="text-white font-semibold text-lg mb-5 flex items-center gap-2">
                        <div
                            class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center text-black text-xs font-bold">
                            3</div>
                        Payment Method
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="gateway" value="paystack" checked class="hidden peer">
                            <div
                                class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200 hover:border-white/20">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fa-solid fa-credit-card text-blue-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-semibold">Paystack</p>
                                        <p class="text-gray-500 text-xs">Card, Transfer, USSD</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="gateway" value="monnify" class="hidden peer">
                            <div
                                class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200 hover:border-white/20">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fa-solid fa-money-bill-transfer text-green-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-semibold">Monnify</p>
                                        <p class="text-gray-500 text-xs">Bank Transfer, USSD</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column — Order Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 glass rounded-2xl p-6">
                    <h2 class="text-white font-semibold mb-5">Order Summary</h2>

                    <div class="space-y-3 mb-5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Ticket</span>
                            <span class="text-white font-medium" x-text="selectedTicketName"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Quantity</span>
                            <span class="text-white" x-text="quantity"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Price per ticket</span>
                            <span class="text-white" x-text="'₦' + ticketPrice.toLocaleString()"></span>
                        </div>

                        @if ($event->payment_model === 'attendee_pays' && $event->commission_rate > 0)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Service fee ({{ $event->commission_rate }}%)</span>
                                <span class="text-gray-400"
                                    x-text="'₦' + Math.round(ticketPrice * quantity * {{ $event->commission_rate / 100 }}).toLocaleString()"></span>
                            </div>
                        @endif

                        <div class="border-t border-white/5 pt-3 flex items-center justify-between">
                            <span class="text-white font-semibold">Total</span>
                            <span class="text-amber-400 font-bold text-lg"
                                x-text="'₦' + Math.round(ticketPrice * quantity * {{ $event->payment_model === 'attendee_pays' ? 1 + $event->commission_rate / 100 : 1 }}).toLocaleString()">
                            </span>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 mb-4 text-red-400 text-xs">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Promo Code -->
                    <div class="mb-4" x-data="{ promoApplied: false, promoMessage: '', promoDiscount: 0 }">
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Promo Code</label>
                        <div class="flex gap-2">
                            <input type="text" id="promo-input" placeholder="Enter code"
                                oninput="this.value = this.value.toUpperCase()"
                                class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all font-mono">
                            <button type="button" onclick="applyPromo()"
                                class="btn-outline-gold px-4 py-2.5 rounded-xl font-semibold text-xs">
                                Apply
                            </button>
                        </div>
                        <p id="promo-message" class="text-xs mt-2 hidden"></p>
                        <input type="hidden" name="promo_code" id="promo-code-value">
                    </div>



                    <button type="submit" class="btn-gold w-full py-4 rounded-xl text-black font-bold text-sm">
                        <i class="fa-solid fa-lock mr-2"></i>
                        Complete Purchase
                    </button>

                    <p class="text-gray-600 text-xs text-center mt-3">
                        <i class="fa-solid fa-shield-halved text-amber-400/50 mr-1"></i>
                        Secured by Paystack & Monnify
                    </p>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        function checkout() {
            return {
                quantity: 1,
                maxQuantity: {{ $selectedTicket ? $selectedTicket->purchase_limit : $event->tickets->first()?->purchase_limit ?? 1 }},
                ticketPrice: {{ $selectedTicket ? $selectedTicket->price : $event->tickets->first()?->price ?? 0 }},
                selectedTicketId: '{{ $selectedTicket ? $selectedTicket->id : $event->tickets->first()?->id ?? '' }}',
                selectedTicketName: '{{ $selectedTicket ? $selectedTicket->name : $event->tickets->first()?->name ?? '' }}',
                buyingForOthers: false,

                updateTicket(price, limit, name, remaining) {
                    this.ticketPrice = price;
                    this.maxQuantity = Math.min(limit, remaining);
                    this.selectedTicketName = name;
                    this.quantity = 1;
                }
            }
        }

        async function applyPromo() {
            const code = document.getElementById('promo-input').value.trim();
            const message = document.getElementById('promo-message');

            if (!code) return;

            // Get selected ticket price and quantity
            const selectedTicket = document.querySelector('input[name="ticket_id"]:checked');
            const quantity = parseInt(document.querySelector('input[name="quantity"]')?.value || 1);

            // Get price from Alpine data
            const priceEl = document.querySelector('[x-text*="ticketPrice"]');

            message.classList.remove('hidden');
            message.className = 'text-xs mt-2 text-gray-400';
            message.textContent = 'Validating...';

            try {
                const response = await fetch('{{ route('promo.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code,
                        event_id: {{ $event->id }},
                        amount: window.checkoutData?.totalAmount ||
                            {{ $event->tickets->where('ticket_type', 'paid')->first()?->price ?? 0 }}
                    })
                });

                const data = await response.json();
                message.classList.remove('hidden');

                if (data.valid) {
                    message.className = 'text-xs mt-2 text-green-400';
                    message.textContent = '✓ ' + data.message + ' — ₦' + Number(data.discount_amount).toLocaleString() +
                        ' off';
                    document.getElementById('promo-code-value').value = code;

                    // Update total display
                    const totalEl = document.querySelector('[x-text*="total"]');
                    if (totalEl) {
                        totalEl.textContent = '₦' + Number(data.new_total).toLocaleString();
                    }
                } else {
                    message.className = 'text-xs mt-2 text-red-400';
                    message.textContent = '✗ ' + data.message;
                    document.getElementById('promo-code-value').value = '';
                }
            } catch (error) {
                message.className = 'text-xs mt-2 text-red-400';
                message.textContent = '✗ Something went wrong. Please try again.';
            }
        }
    </script>
@endpush
