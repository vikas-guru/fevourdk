<?php

namespace App\Mail;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeIndividualDonorMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to FEVOURD-K — A message from the President',
        );
    }

    public function content(): Content
    {
        $displayName = $this->user->first_name
            ?? ($this->user->name !== '' ? $this->user->name : 'Friend');

        return new Content(
            view: 'emails.welcome-individual-donor',
            with: [
                'displayName' => $displayName,
                'intro' => config('fevourd.welcome_email.intro'),
                'presidentLetter' => config('fevourd.welcome_email.letter'),
                'dashboardUrl' => url('/donor/dashboard'),
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(function () {
                return Pdf::loadView('emails.pdf.donor-quick-start', [
                    'displayName' => $this->user->first_name ?: ($this->user->name ?: 'Donor'),
                    'logoDataUri' => $this->imageDataUri(public_path(config('fevourd.brand.logo_public_path'))),
                    'presidentDataUri' => $this->imageDataUri(public_path('assets/President.png')),
                    'presidentName' => 'Shri. Mahesh Chandra Guru',
                    'galleryOneDataUri' => $this->imageDataUri(public_path('assets/images/fevourd-k/about-image.jpg')),
                    'galleryTwoDataUri' => $this->imageDataUri(public_path('assets/images/fevourd-k/events-image.jpg')),
                    'galleryThreeDataUri' => $this->imageDataUri(public_path('assets/images/fevourd-k/programs-image.jpg')),
                ])->setPaper('a4')->output();
            }, 'FEVOURD-K-Donor-Quick-Start-Guide.pdf')->withMime('application/pdf'),
        ];
    }

    private function imageDataUri(string $absolutePath): ?string
    {
        if (! is_file($absolutePath)) {
            return null;
        }

        $content = @file_get_contents($absolutePath);
        if ($content === false) {
            return null;
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };

        return 'data:'.$mime.';base64,'.base64_encode($content);
    }
}
