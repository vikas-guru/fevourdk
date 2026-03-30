<?php

namespace App\Mail;

use App\Models\NgoRegistrationDraft;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NgoChatDraftResumeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NgoRegistrationDraft $draft,
        public string $resumeUrl,
        public string $ngoName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Continue your FEVOURD-K NGO registration',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ngo-chat-draft-resume',
            with: [
                'ngoName' => $this->ngoName,
                'resumeUrl' => $this->resumeUrl,
                'savedAt' => $this->draft->last_saved_at,
            ],
        );
    }
}
