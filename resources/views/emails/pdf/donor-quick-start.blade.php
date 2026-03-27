<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FEVOURD-K Welcome Card</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 0;
            background: #e7eef8;
        }
        .page-wrap {
            padding: 10px;
            background: #e7eef8;
        }
        .page {
            border: 2px solid #1d4ed8;
            border-radius: 14px;
            background: #ffffff;
            padding: 12px 14px;
            box-sizing: border-box;
        }
        .header { background: #1d4ed8; color: #fff; border-radius: 10px; padding: 10px 12px; margin-bottom: 10px; border: 1px solid #1e40af; }
        .row { width: 100%; border-collapse: collapse; }
        .logo-cell { width: 58px; vertical-align: middle; }
        .title { font-size: 19px; font-weight: bold; margin: 0; }
        .sub { font-size: 11px; color: #dbeafe; margin-top: 2px; }
        .welcome { background: #f8fafc; border: 1px solid #dbeafe; border-radius: 10px; padding: 9px; margin-bottom: 8px; }
        .welcome h2 { margin: 0 0 6px; font-size: 16px; color: #0f172a; }
        .welcome p { margin: 0 0 5px; line-height: 1.45; color: #334155; }
        .grid { width: 100%; border-collapse: collapse; }
        .left { width: 52%; vertical-align: top; padding-right: 8px; }
        .right { width: 48%; vertical-align: top; padding-left: 8px; }
        .card { border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px; margin-bottom: 8px; }
        .h3 { font-size: 12px; font-weight: bold; margin: 0 0 5px; color: #0f172a; }
        .list { margin: 0; padding-left: 16px; }
        .list li { margin-bottom: 3px; line-height: 1.35; }
        .president-wrap { border: 1px solid #bfdbfe; border-radius: 10px; background: #eff6ff; padding: 8px; }
        .president-photo-wrap {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            border: 2px solid #93c5fd;
            background: #ffffff;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            box-sizing: border-box;
        }
        .president-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .quote { margin-top: 6px; font-style: italic; color: #1e3a8a; font-size: 10px; line-height: 1.35; }
        .footer { margin-top: 3px; border-top: 1px solid #e2e8f0; padding-top: 4px; color: #64748b; font-size: 10px; }
        .tag { display: inline-block; border: 1px solid #93c5fd; border-radius: 999px; padding: 2px 8px; margin: 2px 4px 0 0; font-size: 10px; color: #1e40af; }
        .gallery { margin-top: 6px; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px; background: #f8fafc; }
        .gallery-title { font-size: 12px; font-weight: bold; color: #0f172a; margin: 0 0 6px; }
        .gallery-grid { width: 100%; border-collapse: collapse; }
        .gallery-cell { width: 33.33%; padding: 3px; vertical-align: top; }
        .gallery-img {
            width: 100%;
            height: 82px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            display: block;
            background: #e2e8f0;
        }
        .gallery-cap { font-size: 9px; color: #475569; margin-top: 3px; line-height: 1.2; }
        .roadmap { margin-top: 6px; border: 1px solid #bfdbfe; border-radius: 10px; padding: 7px; background: #eff6ff; page-break-inside: avoid; }
        .roadmap h4 { margin: 0 0 4px; font-size: 12px; color: #1e3a8a; }
        .roadmap p { margin: 0 0 4px; font-size: 10px; line-height: 1.35; color: #1f2937; }
        .timeline { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .timeline td { border-top: 1px solid #bfdbfe; padding: 4px 3px; font-size: 10px; vertical-align: top; }
        .timeline .day { width: 72px; color: #1e3a8a; font-weight: bold; }
        .closing-wrap {
            margin-top: 5px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            overflow: hidden;
        }
        .closing-banner {
            height: 72px;
            border-bottom: 1px solid #cbd5e1;
            background: linear-gradient(90deg, #dbeafe 0%, #bfdbfe 45%, #dbeafe 100%);
            color: #1e3a8a;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            line-height: 72px;
            letter-spacing: 0.2px;
        }
        .closing-body {
            padding: 6px 8px;
            background: #ffffff;
        }
        .closing-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .closing-left,
        .closing-right {
            width: 50%;
            vertical-align: top;
            padding: 3px 6px;
        }
        .closing-title {
            margin: 0 0 4px;
            font-size: 12px;
            color: #1e3a8a;
            font-weight: bold;
        }
        .closing-text {
            margin: 0 0 3px;
            font-size: 10px;
            color: #334155;
            line-height: 1.35;
        }
        .mini-list {
            margin: 0;
            padding-left: 14px;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.35;
        }
        .mini-list li { margin-bottom: 2px; }
    </style>
</head>
<body>
<div class="page-wrap">
<div class="page">
    <div class="header">
        <table class="row">
            <tr>
                <td class="logo-cell">
                    @if(!empty($logoDataUri))
                        <img src="{{ $logoDataUri }}" alt="FEVOURD-K" width="44" height="44" style="border-radius:8px;background:#fff;padding:4px;">
                    @endif
                </td>
                <td>
                    <p class="title">FEVOURD-K Welcome Card</p>
                    <div class="sub">Federation of Voluntary Organisations for Urban and Rural Development in Karnataka</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="welcome">
        <h2>Welcome, {{ $displayName }}</h2>
        <p>Thank you for joining FEVOURD-K. You are now part of a trusted donor community building measurable social impact across Karnataka.</p>
        <p>Your contribution can directly support verified NGOs, transparent campaigns, and long-term community transformation.</p>
        <div>
            <span class="tag">30+ Years of Service</span>
            <span class="tag">50K+ Lives Impacted</span>
            <span class="tag">Statewide NGO Network</span>
        </div>
    </div>

    <table class="grid">
        <tr>
            <td class="left">
                <div class="card">
                    <p class="h3">What you can do in the system</p>
                    <ul class="list">
                        <li>Explore verified NGOs and active campaigns.</li>
                        <li>Select causes aligned to your values.</li>
                        <li>Track donation impact and progress updates.</li>
                        <li>Stay informed with campaign alerts and reports.</li>
                        <li>Support monthly recurring contributions.</li>
                    </ul>
                </div>

                <div class="card">
                    <p class="h3">Your first impact plan</p>
                    <ul class="list">
                        <li>Complete your donor preferences.</li>
                        <li>Choose one campaign this week.</li>
                        <li>Make your first contribution.</li>
                        <li>Share one campaign with your network.</li>
                    </ul>
                </div>
            </td>

            <td class="right">
                <div class="president-wrap">
                    <table class="row">
                        <tr>
                            <td style="width:94px;vertical-align:top;">
                                @if(!empty($presidentDataUri))
                                    <div class="president-photo-wrap">
                                        <img src="{{ $presidentDataUri }}" alt="{{ $presidentName }}" class="president-photo">
                                    </div>
                                @endif
                            </td>
                            <td style="vertical-align:top;padding-left:8px;">
                                <p style="margin:0;font-size:14px;font-weight:bold;color:#1e3a8a;">{{ $presidentName }}</p>
                                <p style="margin:4px 0 0;font-size:11px;color:#334155;line-height:1.5;">
                                    State President, FEVOURD-K<br>
                                    Visionary leadership, dedication, and statewide impact through collaborative NGO networks.
                                </p>
                            </td>
                        </tr>
                    </table>
                    <div class="quote">
                        "True leadership is about serving others with humility, inspiring positive change, and building bridges between organizations for greater collective impact."
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="gallery">
        <p class="gallery-title">Impact in Action Across Karnataka</p>
        <table class="gallery-grid">
            <tr>
                <td class="gallery-cell">
                    <img class="gallery-img" src="{{ $galleryOneDataUri ?? '' }}" alt="Children education support">
                    <div class="gallery-cap">Education support and child development initiatives</div>
                </td>
                <td class="gallery-cell">
                    <img class="gallery-img" src="{{ $galleryTwoDataUri ?? '' }}" alt="Healthcare outreach">
                    <div class="gallery-cap">Healthcare camps and preventive care outreach</div>
                </td>
                <td class="gallery-cell">
                    <img class="gallery-img" src="{{ $galleryThreeDataUri ?? '' }}" alt="Community volunteering">
                    <div class="gallery-cap">Community volunteering and rural development</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="roadmap">
        <h4>Your 7-Day Professional Donor Roadmap</h4>
        <p>Use this quick plan to move from registration to measurable social impact within one week.</p>
        <table class="timeline">
            <tr>
                <td class="day">Day 1</td>
                <td>Set your profile, interests, and communication preferences for precise campaign recommendations.</td>
            </tr>
            <tr>
                <td class="day">Day 2-3</td>
                <td>Shortlist 2-3 verified NGOs and compare campaign goals, transparency, and expected outcomes.</td>
            </tr>
            <tr>
                <td class="day">Day 4</td>
                <td>Make your first contribution and save the campaign to monitor updates from your dashboard.</td>
            </tr>
            <tr>
                <td class="day">Day 5-7</td>
                <td>Share one campaign with your network and encourage at least one more donor to join your cause.</td>
            </tr>
        </table>
    </div>


    <div class="closing-wrap">
        <div class="closing-banner">Community Impact Across Karnataka</div>
        <div class="closing-body">
            <table class="closing-grid">
                <tr>
                    <td class="closing-left">
                        <p class="closing-title">Thank You for Leading with Compassion</p>
                        <p class="closing-text">
                            Every verified contribution you make strengthens trust, accountability, and long-term social development across Karnataka.
                        </p>
                        <p class="closing-text">
                            Your generosity enables grassroots organizations to reach communities faster and with greater impact.
                        </p>
                    </td>
                    <td class="closing-right">
                        <p class="closing-title">Professional Donor Best Practices</p>
                        <ul class="mini-list">
                            <li>Review NGO updates monthly for outcome visibility.</li>
                            <li>Prefer recurring support for sustained impact.</li>
                            <li>Engage your network to amplify campaigns.</li>
                            <li>Track milestones and share success stories.</li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        Thank you for choosing to serve society with us.<br>
        <strong>Team FEVOURD-K</strong>
    </div>
</div>
</div>
</body>
</html>

