<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcementSubject }}</title>
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

                            <!-- Icon -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom:24px;">
                                        <div
                                            style="width:64px; height:64px; background:rgba(245,158,11,0.15); border:2px solid rgba(245,158,11,0.3); border-radius:18px; display:inline-block; text-align:center; line-height:60px; font-size:28px;">
                                            📢
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:8px;">
                                        <p
                                            style="margin:0; font-size:11px; font-weight:700; color:#f59e0b; text-transform:uppercase; letter-spacing:2px;">
                                            Platform Announcement</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <h1 style="margin:0; font-size:24px; font-weight:900; color:#ffffff;">
                                            {{ $announcementSubject }}</h1>
                                    </td>
                                </tr>
                            </table>

                            <!-- Message -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#252525; border-radius:16px; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:24px;">
                                        <p style="margin:0; color:#d1d5db; font-size:15px; line-height:1.8;">
                                            {{ $announcementMessage }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:rgba(245,158,11,0.06); border:1px solid rgba(245,158,11,0.15); border-radius:12px; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:16px;">
                                        <p style="margin:0; color:#9ca3af; font-size:13px; line-height:1.6;">
                                            This is an official announcement from the {{ config('app.name') team. If you have any
                                            questions, please contact support.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/dashboard') }}"
                                            style="display:inline-block; background:linear-gradient(135deg, #f59e0b, #d97706); color:#000000; text-decoration:none; padding:14px 40px; border-radius:12px; font-weight:800; font-size:14px;">
                                            Go to Dashboard →
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
                                You received this email because you are a registered Event Manager on {{ config('app.name').
                            </p>
                            <p style="margin:0; font-size:12px;">
                                <a href="{{ url('/') }}" style="color:#f59e0b; text-decoration:none;">{{ config('app.name')</a>
                                •
                                <a href="#" style="color:#f59e0b; text-decoration:none;">Terms</a> •
                                <a href="#" style="color:#f59e0b; text-decoration:none;">Privacy</a>
                            </p>
                            <p style="margin:8px 0 0; color:#6b7280; font-size:12px;">© {{ date('Y') }} {{ config('app.name').
                                All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
