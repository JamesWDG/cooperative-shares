<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $emailTemplate;

    /**
     * @param array $mailData
     * @param string $emailTemplate
     */
    public function __construct($mailData, $emailTemplate = 'contact_email')
    {
        $this->mailData = $mailData;
        $this->emailTemplate = $emailTemplate;
    }

    public function envelope()
    {
        return new Envelope(
            subject: $this->mailData['subject'] ?? "CooperativeShares",
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.' . $this->emailTemplate, // e.g. email.contact_email
            with: ['data' => $this->mailData],
        );
    }

    public function attachments()
    {
        return [];
    }
}
