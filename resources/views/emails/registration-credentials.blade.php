<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Login Credentials</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f6fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:#0f172a;padding:18px 22px;color:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:56px;vertical-align:middle;">
                                        <img src="{{ rtrim(config('app.url'), '/') }}/assets/images/fevourd-k/logo.png" alt="FEVOURD-K Logo" width="44" height="44" style="display:block;background:#ffffff;border-radius:8px;padding:4px;" />
                                    </td>
                                    <td style="vertical-align:middle;padding-left:10px;">
                                        <div style="font-size:19px;font-weight:700;letter-spacing:0.3px;color:#ffffff;">FEVOURD-K</div>
                                        <div style="margin-top:4px;font-size:12px;color:#cbd5e1;">Important Account Credentials</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 26px;">
                            <p style="margin:0 0 12px;font-size:15px;line-height:1.6;">Hello <strong>{{ $displayName }}</strong>,</p>
                            <p style="margin:0 0 14px;font-size:14px;line-height:1.7;color:#334155;">
                                Your registration is successful. Keep these credentials safe for future login.
                            </p>

                            <div style="background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:14px 16px;margin:14px 0;">
                                <p style="margin:0 0 8px;font-size:13px;color:#475569;"><strong>Login Email:</strong> {{ $email }}</p>
                                <p style="margin:0;font-size:13px;color:#475569;"><strong>Login PIN:</strong>
                                    <span style="font-family:'Courier New',monospace;font-size:20px;letter-spacing:4px;color:#0f172a;">{{ $plainPin }}</span>
                                </p>
                            </div>

                            <p style="margin:0 0 16px;font-size:13px;color:#b91c1c;">
                                High Priority: Please star/flag this email so you can recover your login details quickly.
                            </p>

                            <p style="margin:0 0 20px;">
                                <a href="{{ $loginUrl }}" style="display:inline-block;background:#111827;color:#ffffff;text-decoration:none;padding:10px 18px;border-radius:8px;font-weight:600;">Go to Login</a>
                            </p>

                            <p style="margin:0;font-size:12px;color:#64748b;">If you did not initiate this registration, contact support immediately.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

