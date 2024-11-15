<?php

namespace Gupta\LaravelInstallerWithEnvato\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SMTPTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct() {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your SMTP settings are working fine',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'installer::mail.smtp-test',
        );
    }
}
