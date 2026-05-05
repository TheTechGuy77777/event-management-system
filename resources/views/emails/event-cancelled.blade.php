<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Cancelled — {{ $event->name }}</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f5; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom: 24px;">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td
                                        style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; width:40px; height:40px; text-align:center; vertical-align:middle;">
                                        <span style="color:#000; font-size:20px; font-weight:900;">⚡</span>
                                    </td>
                                    <td style="padding-left:10px;">
                                        <span style="font-size:22px; font-weight:900; color:#111827;">Event</span>
                                        <span style="font-size:22px; font-weight:900; color:#f59e0b;">Plug</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Card -->
                    <tr>
                        <td style="background:#1a1a1a; border-radius:24px; padding:40px; border:1px solid #2a2a2a;">

                            <!-- Icon -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom:20px;">
                                        <div
                                            style="width:72px; height:72px; background:rgba(239,68,68,0.15); border:2px solid rgba(239,68,68,0.3); border-radius:20px; display:inline-block; text-align:center; line-height:68px; font-size:32px;">
                                            ⚠️
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:8px;">
                                        <h1 style="margin:0; font-size:24px; font-weight:900; color:#ffffff;">
                                            Event Cancelled
                                        </h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <p style="margin:0; color:#9ca3af; font-size:15px;">
                                            We're sorry to inform you that an event you purchased tickets for has been
                                            cancelled.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Event Info -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#252525; border-radius:16px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p
                                            style="margin:0 0 8px; color:#9ca3af; font-size:12px; text-transform:uppercase; letter-spacing:1px;">
                                            Cancelled Event</p>
                                        <p style="margin:0 0 8px; color:#ffffff; font-size:18px; font-weight:800;">
                                            {{ $event->name }}
                                        </p>
                                        <p style="margin:0 0 4px; color:#9ca3af; font-size:13px;">
                                            📅 {{ $event->start_date?->format('l, d F Y') }}
                                        </p>
                                        <p style="margin:0; color:#9ca3af; font-size:13px;">
                                            📍 {{ $event->is_virtual ? 'Virtual Event' : $event->location ?? 'TBA' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Info -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); border-radius:14px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 12px; color:#f87171; font-size:13px; font-weight:700;">Your
                                            Order Details</p>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-bottom:6px;">
                                                    <span style="color:#9ca3af; font-size:13px;">Order Reference</span>
                                                </td>
                                                <td align="right" style="padding-bottom:6px;">
                                                    <span
                                                        style="color:#ffffff; font-family:monospace; font-size:13px; font-weight:700;">{{ $order->payment_reference }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom:6px;">
                                                    <span style="color:#9ca3af; font-size:13px;">Amount Paid</span>
                                                </td>
                                                <td align="right" style="padding-bottom:6px;">
                                                    <span
                                                        style="color:#f59e0b; font-size:13px; font-weight:700;">₦{{ number_format($order->total_amount) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="color:#9ca3af; font-size:13px;">Tickets</span>
                                                </td>
                                                <td align="right">
                                                    <span
                                                        style="color:#ffffff; font-size:13px;">{{ $order->items->count() }}
                                                        ticket(s)</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Refund Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#252525; border-left:3px solid #f59e0b; border-radius:0 12px 12px 0; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:16px;">
                                        <p style="margin:0; color:#d1d5db; font-size:13px; line-height:1.6;">
                                            <strong style="color:#f59e0b;">Refund Information:</strong>
                                            If you paid for this event, a full refund will be processed to your original
                                            payment method within 5-7 business days. Please contact support if you have
                                            any questions.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/') }}"
                                            style="display:inline-block; background:linear-gradient(135deg, #f59e0b, #d97706); color:#000000; text-decoration:none; padding:14px 40px; border-radius:12px; font-weight:800; font-size:14px;">
                                            Discover Other Events →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="margin:0 0 8px; color:#6b7280; font-size:12px;">
                                We apologize for any inconvenience caused.
                            </p>
                            <p style="margin:0; font-size:12px;">
                                <a href="{{ url('/') }}" style="color:#f59e0b; text-decoration:none;">EventPlug</a>
                                •
                                <a href="#" style="color:#f59e0b; text-decoration:none;">Support</a>
                            </p>
                            <p style="margin:8px 0 0; color:#6b7280; font-size:12px;">© {{ date('Y') }} EventPlug.
                                All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
