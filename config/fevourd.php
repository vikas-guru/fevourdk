<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Phone OTP (pilot vs production SMS)
    |--------------------------------------------------------------------------
    | Pilot mode: fixed OTP from OTP_PILOT_CODE (default 1234) — use for NGO
    | pilots without SMS/DLT. Turn off (OTP_PILOT_MODE=false) before production
    | and set SMS_DRIVER=msg91 (or another provider) with real delivery.
    */
    'otp' => [
        /*
         * If OTP_PILOT_MODE is unset: enabled when APP_ENV=local (dev convenience).
         * For NGO pilots on a staging/production server, set OTP_PILOT_MODE=true.
         * Disable (false) when using real SMS (MSG91, etc.).
         */
        'pilot_mode' => filter_var(
            env('OTP_PILOT_MODE', env('APP_ENV') === 'local'),
            FILTER_VALIDATE_BOOLEAN
        ),
        'pilot_code' => (string) env('OTP_PILOT_CODE', '1234'),
    ],

    'president' => [
        'name' => env('FEVOURD_PRESIDENT_NAME', 'President, FEVOURD-K'),
        'title' => env('FEVOURD_PRESIDENT_TITLE', 'Federation of Voluntary Organisations — Karnataka'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Welcome email copy (individual donor registration)
    |--------------------------------------------------------------------------
    | Edit these strings for tone and accuracy. For long letters, prefer short
    | paragraphs so email clients render well on mobile.
    */
    'welcome_email' => [
        'intro' => 'Thank you for registering as an individual donor on FEVOURD-K — Karnataka’s hub for voluntary organisations and transparent giving.',
        'letter' => <<<'EOT'
We are honoured to welcome you to a community that believes in service, accountability, and measurable social impact.

FEVOURD-K connects citizens with verified NGOs and campaigns while ensuring that the platform never pools or holds your donations — each contribution follows the path you choose, in line with our compliance-first design.

Your participation strengthens civil society across Karnataka. We look forward to walking alongside you as we support grassroots organisations and the people they serve.

With warm regards,
EOT
    ],
];
