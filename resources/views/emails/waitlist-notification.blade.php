<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Available — {{ $event->name }}</title>
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
                                        <span style="color:#000; font-size:20px;">⚡</span>
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
                                        <div style="font-size:48px;">🎟️</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:8px;">
                                        <h1 style="margin:0; font-size:24px; font-weight:900; color:#ffffff;">
                                            Good news, {{ $waitlist->name }}!
                                        </h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <p style="margin:0; color:#9ca3af; font-size:15px;">
                                            A ticket just became available for {{ $event->name }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Priority Window Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:rgba(245,158,11,0.1); border:2px solid rgba(245,158,11,0.3); border-radius:16px; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px; text-align:center;">
                                        <p
                                            style="margin:0 0 8px; color:#f59e0b; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                                            ⏰ Priority Booking Window
                                        </p>
                                        <p style="margin:0; color:#ffffff; font-size:28px; font-weight:900;">
                                            30 Minutes
                                        </p>
                                        <p style="margin:8px 0 0; color:#9ca3af; font-size:13px;">
                                            You have 30 minutes to complete your purchase before this offer expires.
                                        </p>
                                        @if ($waitlist->priority_expires_at)
                                            <p style="margin:8px 0 0; color:#f59e0b; font-size:13px; font-weight:700;">
                                                Expires at: {{ $waitlist->priority_expires_at->format('h:i A') }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Ticket Info -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#252525; border-radius:14px; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 8px; color:#9ca3af; font-size:13px;">Ticket Available</p>
                                        <p style="margin:0; color:#ffffff; font-size:16px; font-weight:700;">
                                            {{ $ticket->name }}
                                        </p>
                                        <p style="margin:4px 0 0; color:#f59e0b; font-size:15px; font-weight:700;">
                                            @if ($ticket->ticket_type === 'free')
                                                Free
                                            @else
                                                ₦{{ number_format($ticket->price) }}
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/events/' . $event->slug . '/checkout?ticket=' . $ticket->id) }}"
                                            style="display:inline-block; background:linear-gradient(135deg, #f59e0b, #d97706); color:#000000; text-decoration:none; padding:16px 48px; border-radius:14px; font-weight:800; font-size:15px;">
                                            Book Your Ticket Now →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0; color:#6b7280; font-size:12px; text-align:center;">
                                This offer expires in 30 minutes. After that, the ticket will be offered to the next
                                person on the waitlist.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="margin:0; color:#6b7280; font-size:12px;">
                                © {{ date('Y') }} EventPlug. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
