<?php

namespace App\Mail;

use App\Models\RoadRestriction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestrictionReportedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RoadRestriction $restriction) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Thanks for reporting a road hazard!');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.restriction-reported');
    }
}
