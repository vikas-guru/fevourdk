<?php

namespace App\Services\Sms;

use App\Contracts\SmsSender;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * MSG91 transactional SMS (India). Sign up at https://msg91.com — trial credits are typical for new accounts.
 * Docs: Send SMS API (sendhttp) with route 4 = transactional.
 */
class Msg91SmsSender implements SmsSender
{
    public function __construct(
        private string $authKey,
        private string $senderId,
        private string $route = '4'
    ) {}

    public function send(string $phoneNationalDigits, string $message): void
    {
        if ($this->authKey === '' || $this->senderId === '') {
            throw new RuntimeException('MSG91_AUTH_KEY and MSG91_SENDER_ID must be set when SMS_DRIVER=msg91.');
        }

        $response = Http::timeout(20)->get('https://api.msg91.com/api/sendhttp.php', [
            'authkey' => $this->authKey,
            'mobiles' => $phoneNationalDigits,
            'message' => $message,
            'sender' => $this->senderId,
            'route' => $this->route,
            'country' => 0,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('MSG91 HTTP error: '.$response->body());
        }

        $body = trim($response->body());
        if ($body !== '' && ! ctype_digit($body)) {
            throw new RuntimeException('MSG91 error response: '.$body);
        }
    }
}
