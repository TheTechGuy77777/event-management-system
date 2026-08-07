@php
    $message = 'Hi EventPlug! I have a question about the platform.';
    $link = \App\Helpers\WhatsappSupportLink::build($message);
@endphp

<a href="{{ $link }}" target="_blank" rel="noopener noreferrer" aria-label="Chat with EventPlug Support on WhatsApp"
    title="Need help? Chat with EventPlug Support."
    class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#25D366] flex items-center justify-center shadow-2xl
hover:scale-110 hover:shadow-[0_0_30px_rgba(37,211,102,0.5)] transition-all duration-300 focus:outline-none focus:ring-2
focus:ring-offset-2 focus:ring-offset-[#080808] focus:ring-amber-400">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-7 h-7 fill-white" aria-hidden="true">
        <path
            d="M16.001 3C9.373 3 4 8.373 4 15c0 2.386.7 4.61 1.905 6.472L4 29l7.727-1.867A11.94 11.94 0 0 0 16.001 27C22.629 27 28 21.627 28 15S22.629 3 16.001 3Zm0 21.818a9.77 9.77 0 0 1-4.98-1.363l-.357-.213-4.586 1.108 1.13-4.47-.233-.367A9.77 9.77 0 0 1 6.182 15c0-5.418 4.401-9.818 9.819-9.818S25.818 9.582 25.818 15 21.419 24.818 16.001 24.818Zm5.373-7.34c-.294-.148-1.74-.858-2.01-.956-.27-.098-.467-.148-.663.148-.196.295-.76.956-.932 1.152-.171.196-.343.221-.637.074-.294-.148-1.243-.458-2.368-1.462-.875-.78-1.466-1.744-1.638-2.038-.171-.295-.018-.454.13-.601.134-.133.294-.344.442-.516.147-.171.196-.294.294-.49.098-.196.049-.368-.024-.516-.074-.148-.663-1.6-.909-2.19-.239-.575-.482-.497-.663-.506l-.564-.01a1.08 1.08 0 0 0-.785.368c-.27.295-1.032 1.01-1.032 2.462s1.057 2.855 1.204 3.052c.147.196 2.08 3.178 5.043 4.457.704.304 1.253.486 1.68.622.706.224 1.348.192 1.856.117.566-.085 1.74-.712 1.985-1.4.245-.687.245-1.276.171-1.4-.073-.123-.269-.196-.564-.344Z" />
    </svg>
</a>
