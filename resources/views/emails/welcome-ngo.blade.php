<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome NGO</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Inter,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#1d4ed8;color:#ffffff;padding:20px 24px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:56px;vertical-align:middle;">
                                        @include('emails.partials.brand-mark', ['width' => 44, 'height' => 44])
                                    </td>
                                    <td style="vertical-align:middle;padding-left:12px;">
                                        <h1 style="margin:0;font-size:22px;">Welcome to FEVOURD-K</h1>
                                        <p style="margin:6px 0 0;font-size:14px;opacity:.9;">NGO Digitalization Onboarding Complete</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 24px;color:#0f172a;">
                            <p style="margin:0 0 12px;">Hello {{ $user->name ?: 'NGO Team' }},</p>
                            <p style="margin:0 0 12px;">Your NGO registration has been submitted successfully and is now in verification review.</p>
                            <p style="margin:0 0 12px;"><strong>NGO:</strong> {{ $ngo->name }}</p>
                            <p style="margin:0 0 12px;"><strong>Website URL:</strong> <a href="{{ $websiteUrl }}">{{ $websiteUrl }}</a></p>
                            <p style="margin:0 0 16px;">Keep your NGO login PIN safe. A separate high-priority credentials email is also sent.</p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:12px 0 16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;">
                                <tr>
                                    <td style="padding:14px;">
                                        <p style="margin:0 0 10px;font-size:13px;color:#1e3a8a;font-weight:700;">Welcome Message from President</p>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="78" valign="top" style="padding-right:12px;">
                                                    <img src="{{ $presidentImageUrl }}" alt="President" style="width:66px;height:66px;border-radius:999px;object-fit:cover;border:2px solid #1d4ed8;">
                                                </td>
                                                <td valign="top">
                                                    <p style="margin:0 0 8px;font-size:13px;color:#1e293b;line-height:1.5;">
                                                        We warmly welcome your NGO into the FEVOURD-K ecosystem. Together, we can scale service, trust, and impact across Karnataka.
                                                    </p>
                                                    <p style="margin:0;font-size:12px;color:#1e3a8a;font-weight:700;">{{ $presidentName }}</p>
                                                    <p style="margin:2px 0 0;font-size:12px;color:#334155;">{{ $presidentTitle }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <a href="{{ $loginUrl }}" style="display:inline-block;background:#1d4ed8;color:#fff;text-decoration:none;padding:10px 16px;border-radius:10px;font-weight:600;">Open NGO Login</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
