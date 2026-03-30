<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continue NGO registration</title>
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
                                        <h1 style="margin:0;font-size:20px;">Your NGO registration is saved</h1>
                                        <p style="margin:8px 0 0;font-size:14px;opacity:.9;">FEVOURD-K — continue where you left off</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 24px;color:#0f172a;">
                            <p style="margin:0 0 12px;">Hello,</p>
                            <p style="margin:0 0 12px;">
                                We saved your in-progress registration
                                @if($ngoName !== '')
                                    for <strong>{{ $ngoName }}</strong>
                                @endif
                                on FEVOURD-K.
                            </p>
                            @if($savedAt)
                                <p style="margin:0 0 12px;font-size:13px;color:#64748b;">Last saved: {{ $savedAt->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</p>
                            @endif
                            <p style="margin:0 0 18px;">Use the secure link below on any device. Uploaded documents may need to be re-attached if you switch browsers.</p>
                            <p style="margin:0 0 24px;">
                                <a href="{{ $resumeUrl }}" style="display:inline-block;background:#1d4ed8;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:10px;font-weight:600;">Continue registration</a>
                            </p>
                            <p style="margin:0;font-size:12px;color:#64748b;">If you did not start an NGO registration, you can ignore this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
