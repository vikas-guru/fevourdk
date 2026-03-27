<?php

namespace App\Services\Sms;

use App\Contracts\SmsSender;
use Illuminate\Support\Facades\Log;

class LogSmsSender implements SmsSender
{
    public function send(string $phoneNationalDigits, string $message): void
    {
        Log::info('[SMS log driver] OTP / SMS', [
            'to' => $phoneNationalDigits,
            'message' => $message,
        ]);
    }
}
