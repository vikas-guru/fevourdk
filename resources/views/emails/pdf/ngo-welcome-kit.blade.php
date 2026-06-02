<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #14213d; font-size: 12px; line-height: 1.5; }
        .page { padding: 36px 40px; }
        .header { border-bottom: 3px solid #f2b40c; padding-bottom: 16px; margin-bottom: 22px; }
        .brand { font-size: 22px; font-weight: bold; color: #14213d; letter-spacing: .5px; }
        .brand span { color: #b8860b; }
        .tagline { color: #6b7280; font-size: 11px; margin-top: 2px; }
        .ack { background: #fbf7ea; border: 1px solid #f0e2b8; border-radius: 8px; padding: 14px 16px; margin-bottom: 22px; }
        .ack h1 { font-size: 16px; color: #14213d; margin-bottom: 4px; }
        .ack p { color: #6b7280; font-size: 11px; }
        .section-title { font-size: 13px; font-weight: bold; color: #b8860b; text-transform: uppercase;
            letter-spacing: .5px; border-bottom: 1px solid #eee; padding-bottom: 5px; margin: 20px 0 10px; }
        table.kv { width: 100%; border-collapse: collapse; }
        table.kv td { padding: 6px 8px; vertical-align: top; border-bottom: 1px solid #f1f1f1; }
        table.kv td.k { width: 35%; color: #6b7280; font-size: 11px; text-transform: uppercase; letter-spacing: .3px; }
        table.kv td.v { color: #14213d; font-weight: bold; }
        ol.steps { margin: 6px 0 0 18px; }
        ol.steps li { margin-bottom: 7px; }
        .badge { display: inline-block; background: #14213d; color: #fff; padding: 3px 10px; border-radius: 999px; font-size: 10px; }
        .badge.verified { background: #166534; }
        .footer { margin-top: 28px; padding-top: 14px; border-top: 1px solid #eee; color: #9ca3af; font-size: 10px; text-align: center; }
        .pin { color: #b8860b; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="brand">FEVOURD<span>-K</span></div>
        <div class="tagline">Federation of Voluntary Organisations for Urban &amp; Rural Development — Karnataka</div>
    </div>

    <div class="ack">
        <h1>NGO Registration Acknowledgement</h1>
        <p>This document confirms that <strong>{{ $ngo->name }}</strong> has been registered on the FEVOURD-K platform on {{ $generatedAt }}.</p>
    </div>

    <div class="section-title">Organisation details</div>
    <table class="kv">
        <tr><td class="k">Organisation</td><td class="v">{{ $ngo->name }}</td></tr>
        @if($ngo->registration_number)
            <tr><td class="k">Registration No.</td><td class="v">{{ $ngo->registration_number }}</td></tr>
        @endif
        @if($ngo->pan)
            <tr><td class="k">PAN</td><td class="v">{{ $ngo->pan }}</td></tr>
        @endif
        <tr><td class="k">Status</td><td class="v">
            <span class="badge {{ $ngo->verification_status === 'verified' ? 'verified' : '' }}">
                {{ $ngo->verification_status === 'verified' ? 'Verified NGO' : 'Registered' }}
            </span>
        </td></tr>
        @if($ngo->address)
            <tr><td class="k">Address</td><td class="v">{{ $ngo->address }}</td></tr>
        @endif
        @if(optional($ngo->city)->name ?? $ngo->city_id)
            <tr><td class="k">Location</td><td class="v">{{ optional($ngo->city)->name }}{{ optional($ngo->city)->name ? ', ' : '' }}Karnataka</td></tr>
        @endif
        <tr><td class="k">Contact email</td><td class="v">{{ $ngo->email ?? $user->email }}</td></tr>
        @if($ngo->phone)
            <tr><td class="k">Phone</td><td class="v">{{ $ngo->phone }}</td></tr>
        @endif
        <tr><td class="k">Microsite</td><td class="v">{{ $websiteUrl }}</td></tr>
    </table>

    <div class="section-title">Account</div>
    <table class="kv">
        <tr><td class="k">Administrator</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Login email</td><td class="v">{{ $user->email }}</td></tr>
        <tr><td class="k">Sign in at</td><td class="v">{{ $loginUrl }}</td></tr>
    </table>

    <div class="section-title">Next steps</div>
    <ol class="steps">
        <li>Sign in and complete your organisation profile (logo, description, focus areas, leadership).</li>
        <li>Upload your registration certificate and PAN to get the <strong>Verified NGO</strong> badge.</li>
        <li>Publish your free microsite and share it: <span class="pin">{{ $websiteUrl }}</span></li>
        <li>Create campaigns to receive transparent, tracked donations.</li>
    </ol>

    <div class="footer">
        FEVOURD-K · Karnataka voluntary ecosystem platform · This is a system-generated acknowledgement.<br>
        {{ $presidentName }}{{ $presidentName ? ' — '.$presidentTitle : '' }}
    </div>
</div>
</body>
</html>
