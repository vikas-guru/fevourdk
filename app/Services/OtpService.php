<?php

namespace App\Services;

use App\Contracts\SmsSender;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public function __construct(
        private SmsSender $smsSender
    ) {}

    public function isPilotMode(): bool
    {
        return (bool) config('fevourd.otp.pilot_mode');
    }

    /**
     * Expected OTP length for forms and validation (4 in pilot with default 1234, else 6).
     */
    public function otpCodeLength(): int
    {
        if ($this->isPilotMode()) {
            $digits = preg_replace('/\D/', '', (string) config('fevourd.otp.pilot_code')) ?: '1234';

            return strlen($digits);
        }

        return 6;
    }

    /**
     * Normalize Indian mobile to 91XXXXXXXXXX digits only.
     */
    public function normalizeIndianPhone(string $input): ?string
    {
        $digits = preg_replace('/\D/', '', $input) ?? '';
        if (strlen($digits) === 10) {
            return '91'.$digits;
        }
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return $digits;
        }

        return null;
    }

    /**
     * Send OTP SMS. Returns plaintext for API when pilot mode or local dev (for QA UI).
     */
    public function send(string $rawPhone): ?string
    {
        $phone = $this->normalizeIndianPhone($rawPhone);
        if ($phone === null) {
            throw ValidationException::withMessages([
                'phone' => ['Enter a valid 10-digit Indian mobile number.'],
            ]);
        }

        $this->assertSendRateLimit($phone);

        if ($this->isPilotMode()) {
            $otp = preg_replace('/\D/', '', (string) config('fevourd.otp.pilot_code')) ?: '1234';
            if (strlen($otp) < 4 || strlen($otp) > 6) {
                $otp = '1234';
            }
        } else {
            $otp = (string) random_int(100000, 999999);
        }

        $hash = hash_hmac('sha256', $otp, (string) config('app.key'));

        DB::transaction(function () use ($phone, $hash) {
            OtpVerification::query()
                ->where('phone', $phone)
                ->whereNull('consumed_at')
                ->delete();

            OtpVerification::query()->create([
                'phone' => $phone,
                'code_hash' => $hash,
                'expires_at' => now()->addMinutes(10),
            ]);
        });

        $message = $this->isPilotMode()
            ? 'FEVOURD-K pilot: your test verification code is '.$otp.'. Do not use in production.'
            : 'Your FEVOURD-K verification code is '.$otp.'. Valid for 10 minutes. Do not share this code.';
        $this->smsSender->send($phone, $message);

        if ($this->isPilotMode()) {
            return $otp;
        }

        return app()->environment('local') ? $otp : null;
    }

    public function verify(string $rawPhone, string $otp): bool
    {
        $phone = $this->normalizeIndianPhone($rawPhone);
        if ($phone === null) {
            return false;
        }

        $otp = trim($otp);
        $expectedLen = $this->otpCodeLength();
        if (strlen($otp) !== $expectedLen || ! ctype_digit($otp)) {
            return false;
        }

        $record = OtpVerification::query()
            ->where('phone', $phone)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->first();

        if (! $record) {
            return false;
        }

        if ($record->attempts >= 6) {
            return false;
        }

        $record->increment('attempts');

        $expected = hash_hmac('sha256', $otp, (string) config('app.key'));
        if (! hash_equals($record->code_hash, $expected)) {
            return false;
        }

        $record->update(['consumed_at' => now()]);

        return true;
    }

    private function assertSendRateLimit(string $phone): void
    {
        $count = OtpVerification::query()
            ->where('phone', $phone)
            ->where('created_at', '>', now()->subHour())
            ->count();

        if ($count >= 5) {
            throw ValidationException::withMessages([
                'phone' => ['Too many OTP requests. Please try again after some time.'],
            ]);
        }
    }
}
