<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets — {{ $event->name }}</title>
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
                                        <span style="font-size:22px; font-weight:900; color:#111827;">Event</span><span
                                            style="font-size:22px; font-weight:900; color:#f59e0b;">Plug</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Card -->
                    <tr>
                        <td style="background:#1a1a1a; border-radius:24px; padding:40px; border:1px solid #2a2a2a;">

                            <!-- Success Icon -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom:24px;">
                                        <div
                                            style="width:80px; height:80px; background:rgba(34,197,94,0.15); border:2px solid rgba(34,197,94,0.4); border-radius:20px; display:inline-block; text-align:center; line-height:76px; font-size:40px;">
                                            ✅
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:8px;">
                                        <h1 style="margin:0; font-size:28px; font-weight:900; color:#ffffff;">You're In!
                                            🎉</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <p style="margin:0; color:#9ca3af; font-size:15px;">Your tickets are confirmed.
                                            See you at the event!</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Event Details -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#252525; border-radius:16px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 12px; font-size:18px; font-weight:800; color:#ffffff;">
                                            {{ $event->name }}</p>
                                        <p style="margin:0 0 8px; font-size:13px; color:#9ca3af;">
                                            📅 {{ $event->start_date?->format('l, d F Y \a\t h:i A') }}
                                        </p>
                                        <p style="margin:0; font-size:13px; color:#9ca3af;">
                                            📍
                                            {{ $event->event_mode === 'online' ? 'Online Event' : $event->location ?? 'TBA' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            @if (in_array($event->event_mode, ['online', 'hybrid']))
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="background:#252525; border-radius:16px; margin-bottom:20px; border:1px solid rgba(59,130,246,0.3);">
                                    <tr>
                                        <td style="padding:20px;">
                                            <p style="margin:0 0 12px; font-size:15px; font-weight:700; color:#ffffff;">
                                                🎥 Join Online
                                            </p>

                                            @if ($event->meeting_link)
                                                <p style="margin:0 0 4px; font-size:12px; color:#9ca3af;">
                                                    Meeting Link
                                                    ({{ ucwords(str_replace('_', ' ', $event->platform)) }})
                                                </p>
                                                <p style="margin:0 0 12px;">
                                                    <a href="{{ $event->meeting_link }}"
                                                        style="color:#f59e0b; font-size:13px; word-break:break-all;">
                                                        {{ $event->meeting_link }}
                                                    </a>
                                                </p>
                                            @endif

                                            @if ($event->meeting_id)
                                                <p style="margin:0 0 4px; font-size:12px; color:#9ca3af;">
                                                    Meeting ID: <span
                                                        style="color:#ffffff; font-family:monospace;">{{ $event->meeting_id }}</span>
                                                </p>
                                            @endif

                                            @if ($event->meeting_passcode)
                                                <p style="margin:0 0 12px; font-size:12px; color:#9ca3af;">
                                                    Passcode: <span
                                                        style="color:#ffffff; font-family:monospace;">{{ $event->meeting_passcode }}</span>
                                                </p>
                                            @endif

                                            @if ($event->whatsapp_link)
                                                <p
                                                    style="margin:12px 0 0; padding-top:12px; border-top:1px solid #333;">
                                                    <a href="{{ $event->whatsapp_link }}"
                                                        style="color:#22c55e; font-size:13px; font-weight:700;">
                                                        💬 Join WhatsApp Group
                                                    </a>
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <!-- Tickets -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                                <tr>
                                    <td style="padding-bottom:12px;">
                                        <p style="margin:0; font-size:15px; font-weight:700; color:#ffffff;">🎫 Your
                                            Tickets</p>
                                    </td>
                                </tr>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td style="padding-bottom:10px;">
                                            <table width="100%" cellpadding="0" cellspacing="0"
                                                style="background:#252525; border-radius:12px; padding:0;">
                                                <tr>
                                                    <td style="padding:16px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td>
                                                                    <p
                                                                        style="margin:0 0 4px; font-size:14px; font-weight:700; color:#ffffff;">
                                                                        {{ $item->attendee_name }}</p>
                                                                    <p style="margin:0; font-size:12px; color:#6b7280;">
                                                                        {{ $item->ticket->name ?? 'General Admission' }}
                                                                    </p>
                                                                </td>
                                                                <td align="right">
                                                                    <span
                                                                        style="background:rgba(245,158,11,0.15); border:1px solid rgba(245,158,11,0.3); color:#f59e0b; font-family:monospace; font-size:15px; font-weight:900; padding:6px 14px; border-radius:8px; display:inline-block;">
                                                                        {{ $item->ticket_code }}
                                                                    </span>
                                                            <tr>
                                                                <td colspan="2"
                                                                    style="padding-top:12px; text-align:center;">
                                                                    <div
                                                                        style="background:rgba(245,158,11,0.1); border:2px dashed rgba(245,158,11,0.4); border-radius:12px; padding:16px; display:inline-block;">
                                                                        @if ($item->qr_code $item->qr_code && $event->event_mode !== 'online')
                                                                            <img src="{{ url('storage/' . $item->qr_code) }}"
                                                                                alt="Ticket QR Code" width="140"
                                                                                height="140"
                                                                                style="display:block; margin:0 auto 10px;">
                                                                        @endif
                                                                        <p
                                                                            style="margin:0 0 6px; color:#9ca3af; font-size:11px; text-transform:uppercase; letter-spacing:1px;">
                                                                            Your Ticket Code</p>
                                                                        <p
                                                                            style="margin:0; color:#f59e0b; font-family:monospace; font-size:24px; font-weight:900; letter-spacing:3px;">
                                                                            {{ $item->ticket_code }}</p>
                                                                        <p
                                                                            style="margin:6px 0 0; color:#6b7280; font-size:11px;">
                                                                            Present this code at the entrance</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                            </table>
                        </td>
                    </tr>
                    @endforeach

                    <!-- Total -->
                    <tr>
                        <td style="padding-top:12px; border-top:1px solid #2a2a2a;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <p style="margin:0; font-size:15px; font-weight:700; color:#ffffff;">
                                            Total Paid</p>
                                    </td>
                                    <td align="right">
                                        <p style="margin:0; font-size:20px; font-weight:900; color:#f59e0b;">
                                            ₦{{ number_format($order->total_amount) }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Order Reference -->
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2); border-radius:14px; margin-bottom:20px;">
                    <tr>
                        <td style="padding:20px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-bottom:8px;">
                                        <span style="color:#9ca3af; font-size:13px;">Order Reference</span>
                                    </td>
                                    <td align="right" style="padding-bottom:8px;">
                                        <span
                                            style="color:#f59e0b; font-family:monospace; font-size:13px; font-weight:700;">{{ $order->payment_reference }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:8px;">
                                        <span style="color:#9ca3af; font-size:13px;">Payment Method</span>
                                    </td>
                                    <td align="right" style="padding-bottom:8px;">
                                        <span
                                            style="color:#f59e0b; font-size:13px; font-weight:700;">{{ ucfirst($order->payment_gateway) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="color:#9ca3af; font-size:13px;">Purchase Date</span>
                                    </td>
                                    <td align="right">
                                        <span
                                            style="color:#f59e0b; font-size:13px; font-weight:700;">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Notice -->
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="background:#252525; border-left:3px solid #f59e0b; border-radius:0 12px 12px 0; margin-bottom:24px;">
                    <tr>
                        <td style="padding:16px;">
                            <p style="margin:0; color:#9ca3af; font-size:13px; line-height:1.6;">
                                <strong style="color:#f59e0b;">Important:</strong>
                                Present your unique ticket code at the entrance to gain access.
                                Each code is valid for one entry only. Your ticket code is:
                                <strong
                                    style="color:#f59e0b; font-family:monospace;">{{ $order->items->first()?->ticket_code }}</strong>
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- CTA Button -->
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <a href="{{ url('/events/' . $event->slug) }}"
                                style="display:inline-block; background:linear-gradient(135deg, #f59e0b, #d97706); color:#000000; text-decoration:none; padding:16px 48px; border-radius:14px; font-weight:800; font-size:15px;">
                                View Event Details →
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
                    This email was sent by <strong>{{ config('app.name')</strong> on behalf of the event organizer.
                </p>
                <p style="margin:0 0 8px; font-size:12px;">
                    <a href="{{ url('/') }}" style="color:#f59e0b; text-decoration:none;">{{ config('app.name')</a> •
                    <a href="#" style="color:#f59e0b; text-decoration:none;">Terms</a> •
                    <a href="#" style="color:#f59e0b; text-decoration:none;">Privacy</a>
                </p>
                <p style="margin:0; color:#6b7280; font-size:12px;">© {{ date('Y') }} {{ config('app.name'). All
                    rights reserved.</p>
            </td>
        </tr>

    </table>
    </td>
    </tr>
    </table>

</body>

</html>
