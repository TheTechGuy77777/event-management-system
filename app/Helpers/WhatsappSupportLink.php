<?php

namespace App\Helpers;

class WhatsappSupportLink
{
    public static function build(string $message): string
    {
        $number = config('services.whatsapp.support_number');

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }
}
