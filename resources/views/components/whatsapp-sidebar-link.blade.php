@php
    $userName = auth()->user()->name ?? '';
    $userEmail = auth()->user()->email ?? '';
    $supportNumber = config('services.whatsapp.support_number');
@endphp

<div x-data="{
    open: false,
    message: '',
    get whatsappUrl() {
        const text = `Hi {{ config('app.name') }}! This is {{ addslashes($userName) }} ({{ addslashes($userEmail) }}).\n\n${this.message}`;
        return `https://wa.me/{{ $supportNumber }}?text=${encodeURIComponent(text)}`;
    }
}">
    <button type="button" @click="open = true"
        aria-label="Need help? Chat with {{ config('app.name') }} Support on WhatsApp"
        title="Need help? Chat with {{ config('app.name') }} Support."
        class="sidebar-link w-full text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[#0d0d0d]">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="sidebar-icon" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3.68-3.68a1.5 1.5 0 0 0-1.06-.44H9a2.25 2.25 0 0 1-2.25-2.25v-4.286c0-.97.616-1.813 1.5-2.097M15.75 8.25v-1.5a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25v6a2.25 2.25 0 0 0 2.25 2.25h1.5v3.75l3.75-3.75H12" />
        </svg>
        Support
    </button>

    {{-- Modal --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="glass rounded-3xl p-8 w-full max-w-md relative z-10 border border-amber-400/20">
            <div class="text-center mb-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-[#25D366]/15 border border-[#25D366]/30 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-brands fa-whatsapp text-[#25D366] text-2xl"></i>
                </div>
                <h3 class="text-white font-bold text-xl mb-1">Chat with Support</h3>
                <p class="text-gray-500 text-sm">We'll open WhatsApp with your message ready to send.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-gray-400 text-sm font-medium mb-2 block">Your Message</label>
                    <textarea x-model="message" rows="4" placeholder="What do you need help with?"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all resize-none"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="open = false"
                        class="flex-1 btn-outline-gold py-3 rounded-xl font-semibold text-sm">
                        Cancel
                    </button>
                    <button type="button" @click="window.open(whatsappUrl, '_blank'); open = false; message = ''"
                        :disabled="!message.trim()"
                        class="flex-1 btn-gold py-3 rounded-xl text-black font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-brands fa-whatsapp mr-2"></i>Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
