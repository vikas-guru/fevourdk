<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPin
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Important: Your FEVOURD-K Login Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-credentials',
            with: [
                'displayName' => $this->user->first_name ?: ($this->user->name ?: 'User'),
                'email' => $this->user->email,
                'plainPin' => $this->plainPin,
                'loginUrl' => url('/login'),
            ],
        );
    }
}

