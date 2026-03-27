<?php

namespace App\Contracts;

interface SmsSender
{
    /**
     * @param  string  $phoneNationalDigits  Indian mobile as 91XXXXXXXXXX (no +)
     */
    public function send(string $phoneNationalDigits, string $message): void;
}
