<?php

namespace App\Mail;

use App\Models\NGO;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNgoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NGO $ngo,
        public User $user
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to FEVOURD-K NGO Digitalization',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-ngo',
            with: [
                'ngo' => $this->ngo,
                'user' => $this->user,
                'loginUrl' => url('/login'),
                'websiteUrl' => $this->ngo->website_url ?: url('/'.$this->ngo->slug),
                'presidentName' => (string) config('fevourd.president.name'),
                'presidentTitle' => (string) config('fevourd.president.title'),
                'presidentImageUrl' => asset('assets/President.png'),
            ]
        );
    }

    /**
     * Attach an NGO Welcome Kit / registration acknowledgement PDF.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(function () {
                return Pdf::loadView('emails.pdf.ngo-welcome-kit', [
                    'ngo' => $this->ngo,
                    'user' => $this->user,
                    'loginUrl' => url('/login'),
                    'websiteUrl' => $this->ngo->website_url ?: url('/'.$this->ngo->slug),
                    'generatedAt' => now()->format('d M Y'),
                    'presidentName' => (string) config('fevourd.president.name'),
                    'presidentTitle' => (string) config('fevourd.president.title'),
                ])->setPaper('a4')->output();
            }, 'FEVOURD-K-NGO-Welcome-Kit.pdf')->withMime('application/pdf'),
        ];
    }
}
