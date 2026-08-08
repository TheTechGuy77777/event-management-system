@php
    $message = 'Hi ' . config('app.name') . '! I need help with my organizer dashboard.';
    $link = \App\Helpers\WhatsappSupportLink::build($message);
@endphp

<a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
    aria-label="Chat with {{ config('app.name') }} Support on WhatsApp"
    title="Need help? Chat with {{ config('app.name') }} Support."
    class="sidebar-link focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400/60 focus-visible:ring-offset-2
focus-visible:ring-offset-[#0d0d0d]">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="sidebar-icon" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3.68-3.68a1.5 1.5 0 0 0-1.06-.44H9a2.25 2.25 0 0 1-2.25-2.25v-4.286c0-.97.616-1.813 1.5-2.097M15.75 8.25v-1.5a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25v6a2.25 2.25 0 0 0 2.25 2.25h1.5v3.75l3.75-3.75H12" />
    </svg>
    Support
</a>
