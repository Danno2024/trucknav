<?php

namespace App\Mail;

use App\Models\SavedRoute;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RouteSavedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SavedRoute $route) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Route saved: '.$this->route->name);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.route-saved');
    }
}
