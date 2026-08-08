<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body
    style="margin:0; padding:0; background-color:#0f0f10; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color:#0f0f10; padding: 40px 16px;">
        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:480px; background-color:#1a1a1c; border-radius:20px; overflow:hidden; border:1px solid rgba(255,255,255,0.08);">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 36px 32px 8px 32px;">
                            <div style="font-size:20px; font-weight:700; color:#f5b942; letter-spacing:0.5px;">
                                {{ config('app.name') }}
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 16px 32px 8px 32px;">
                            <p
                                style="color:#f5f5f5; font-size:18px; font-weight:600; margin:0 0 8px 0; text-align:center;">
                                Verify your email
                            </p>
                            <p
                                style="color:#a1a1aa; font-size:14px; line-height:22px; margin:0 0 28px 0; text-align:center;">
                                Hi {{ $name }}, use the code below to activate your {{ config('app.name') }}
                                account.
                            </p>
                        </td>
                    </tr>

                    <!-- OTP Code -->
                    <tr>
                        <td align="center" style="padding: 0 32px 28px 32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td
                                        style="background-color:rgba(245,185,66,0.08); border:1px solid rgba(245,185,66,0.35); border-radius:14px; padding:18px 28px;">
                                        <span
                                            style="font-size:34px; font-weight:700; letter-spacing:10px; color:#f5b942; font-family: 'Courier New', monospace;">
                                            {{ $code }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Expiry note -->
                    <tr>
                        <td style="padding: 0 32px 32px 32px;">
                            <p style="color:#71717a; font-size:13px; line-height:20px; margin:0; text-align:center;">
                                This code expires in <strong style="color:#a1a1aa;">10 minutes</strong>.
                                If you didn't request this, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 32px;">
                            <div style="border-top:1px solid rgba(255,255,255,0.08);"></div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 24px 32px 32px 32px;">
                            <p style="color:#52525b; font-size:12px; margin:0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
