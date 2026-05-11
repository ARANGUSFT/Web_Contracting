<?php

namespace App\Mail;

use App\Models\Subcontractors;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubcontractorWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public Subcontractors $subcontractor;
    public string $plainPassword;
    public string $googlePlayUrl;
    public string $appStoreUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Subcontractors $subcontractor, string $plainPassword)
    {
        $this->subcontractor = $subcontractor;
        $this->plainPassword = $plainPassword;
        $this->googlePlayUrl = 'https://play.google.com/store/apps/details?id=com.contractingallianceinc.miapp.v2&pcampaignid=web_share';
        $this->appStoreUrl   = 'https://apps.apple.com/us/app/contracting-alliance-inc/id6756724685';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Contracting Alliance — Your Account Credentials',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subcontractor-welcome',
            with: [
                'subcontractor' => $this->subcontractor,
                'plainPassword' => $this->plainPassword,
                'googlePlayUrl' => $this->googlePlayUrl,
                'appStoreUrl'   => $this->appStoreUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}