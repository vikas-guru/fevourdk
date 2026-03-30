<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your OTP Code</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f6fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:#1d4ed8;padding:18px 22px;color:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:56px;vertical-align:middle;">
                                        @include('emails.partials.brand-mark', ['width' => 44, 'height' => 44])
                                    </td>
                                    <td style="vertical-align:middle;padding-left:10px;">
                                        <div style="font-size:19px;font-weight:700;letter-spacing:0.3px;color:#ffffff;line-height:1.2;">FEVOURD-K</div>
                                        <div style="margin-top:4px;font-size:12px;color:#dbeafe;">Phone Verification Code</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.6;">
                                Use this One-Time Password (OTP) to continue your registration.
                            </p>

                            <div style="margin:18px 0;padding:16px;border:1px dashed #93c5fd;border-radius:12px;background:#eff6ff;text-align:center;">
                                <div style="font-size:12px;letter-spacing:1px;color:#1d4ed8;font-weight:600;text-transform:uppercase;">Your OTP</div>
                                <div style="margin-top:8px;font-size:34px;letter-spacing:8px;font-weight:800;color:#0f172a;font-family:'Courier New',monospace;">{{ $otp }}</div>
                            </div>

                            <p style="margin:0 0 10px;font-size:14px;color:#4b5563;">
                                This OTP is valid for <strong>{{ $minutes }} minutes</strong>.
                                @if(!empty($phone))
                                    It was requested for phone: <strong>{{ $phone }}</strong>.
                                @endif
                            </p>

                            <p style="margin:0;font-size:13px;color:#b91c1c;">
                                Security tip: Never share this OTP with anyone.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;background:#f8fafc;color:#64748b;font-size:12px;line-height:1.5;">
                            This is an automated message from {{ config('app.name') }}.
                            If you did not request this OTP, you can safely ignore this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
