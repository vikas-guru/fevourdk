<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    | SMS for phone OTP (India). "log" writes to Laravel log (free, for dev).
    | "msg91" uses MSG91 transactional route (trial credits; then paid).
    | Alternatives: 2Factor.in, AWS SNS, Twilio, Fast2SMS (compare pricing & DLT).
    */
    'sms' => [
        'driver' => env('SMS_DRIVER', 'log'),
        'msg91' => [
            'auth_key' => env('MSG91_AUTH_KEY'),
            'sender_id' => env('MSG91_SENDER_ID'),
            'route' => env('MSG91_ROUTE', '4'),
        ],
    ],

    /*
    | IP geolocation for NGO login audit / policy (uses ip-api.com HTTP API; cache + timeout).
    | Disable with IP_GEO_ENABLED=false if outbound HTTP is blocked.
    */
    'ip_geo' => [
        'enabled' => env('IP_GEO_ENABLED', true),
        'timeout' => env('IP_GEO_TIMEOUT', 2),
        'cache_ttl' => env('IP_GEO_CACHE_TTL', 3600),
    ],

    /*
    | Google AdSense (optional). Set ADSENSE_ENABLED=true and ADSENSE_CLIENT_ID=ca-pub-xxxxxxxx
    | Use your real publisher ID from AdSense. ads.txt is served at /ads.txt when configured.
    | Note: ads usually do not appear on http://127.0.0.1 or localhost; test on your live domain.
    */
    'adsense' => [
        'enabled' => env('ADSENSE_ENABLED', false),
        'client_id' => env('ADSENSE_CLIENT_ID'),
    ],

];
