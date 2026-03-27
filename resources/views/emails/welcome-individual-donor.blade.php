<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to FEVOURD-K</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f6fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:700px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:#1d4ed8;padding:18px 22px;color:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:56px;vertical-align:middle;">
                                        <img src="{{ rtrim(config('app.url'), '/') }}/assets/images/fevourd-k/logo.png" alt="FEVOURD-K Logo" width="44" height="44" style="display:block;background:#ffffff;border-radius:8px;padding:4px;" />
                                    </td>
                                    <td style="vertical-align:middle;padding-left:10px;">
                                        <div style="font-size:19px;font-weight:700;letter-spacing:0.3px;color:#ffffff;">FEVOURD-K</div>
                                        <div style="margin-top:4px;font-size:12px;color:#dbeafe;">Welcome to the donor community</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 26px;">
                            <h1 style="margin:0 0 12px;font-size:30px;line-height:1.2;color:#0f172a;">Welcome, {{ $displayName }}</h1>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;color:#334155;">
                                {{ $intro }}
                            </p>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;color:#334155;">
                                You are now part of a powerful movement working to strengthen lives and communities across Karnataka.
                                Start by exploring verified NGOs and choosing one campaign where your contribution can create measurable impact this week.
                            </p>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;color:#334155;">{!! nl2br(e($presidentLetter)) !!}</p>
                            <div style="background:#f8fafc;border:1px solid #dbeafe;border-radius:10px;padding:12px 14px;margin-bottom:16px;">
                                <p style="margin:0 0 8px;font-size:13px;color:#1e3a8a;font-weight:700;">Your first impact checklist</p>
                                <ul style="margin:0;padding-left:18px;color:#334155;font-size:13px;line-height:1.7;">
                                    <li>Complete donor profile and communication preferences.</li>
                                    <li>Follow 2-3 NGOs aligned with your interests.</li>
                                    <li>Make your first contribution and track updates from dashboard.</li>
                                </ul>
                            </div>
                            <p style="margin:0 0 22px;">
                                <a href="{{ $dashboardUrl }}" style="display:inline-block;background:#111827;color:#ffffff;text-decoration:none;padding:10px 18px;border-radius:8px;font-weight:600;">View donor dashboard</a>
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #dbeafe;background:#eff6ff;border-radius:12px;">
                                <tr>
                                    <td style="padding:16px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="width:116px;vertical-align:top;">
                                                    <img src="{{ rtrim(config('app.url'), '/') }}/assets/President.png" alt="Shri. Mahesh Chandra Guru" width="96" height="120" style="display:block;border-radius:10px;object-fit:cover;border:1px solid #bfdbfe;" />
                                                </td>
                                                <td style="vertical-align:top;padding-left:14px;">
                                                    <h3 style="margin:0 0 6px;font-size:18px;color:#1e3a8a;">Shri. Mahesh Chandra Guru</h3>
                                                    <p style="margin:0 0 8px;font-size:13px;color:#1e40af;font-weight:700;">State President, FEVOURD-K | Federation of Voluntary Organisations for Urban and Rural Development in Karnataka</p>
                                                    <p style="margin:0 0 8px;font-size:13px;line-height:1.6;color:#1f2937;">
                                                        Visionary leadership with 30+ years of service, impacting 50K+ lives across Karnataka through coordinated NGO collaboration, compassionate governance, and statewide social development initiatives.
                                                    </p>
                                                    <p style="margin:0;font-size:12px;line-height:1.6;color:#334155;">
                                                        "True leadership is about serving others with humility, inspiring positive change, and building bridges between organizations for greater collective impact."
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:18px 0 0;font-size:13px;color:#475569;">
                                <strong>Statewide Impact Through FEVOURD-K:</strong> Thousands of voluntary organizations collaborate for urban and rural development in Karnataka under this leadership network.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px 24px;background:#f8fafc;color:#64748b;font-size:12px;line-height:1.6;">
                            {{ config('app.name') }} | This is an automated welcome email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
