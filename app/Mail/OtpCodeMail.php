<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public int $minutes = 10,
        public ?string $phone = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your FEVOURD-K OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-code',
            with: [
                'otp' => $this->otp,
                'minutes' => $this->minutes,
                'phone' => $this->phone,
            ],
        );
    }
}
