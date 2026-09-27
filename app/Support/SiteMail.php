<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SiteMail
{
    /**
     * Send a mailable if its admin toggle is enabled.
     * Mail failures are logged, never thrown — a broken SMTP
     * config must not break registration, saving, etc.
     */
    public static function sendIfEnabled(string $toggleKey, Mailable $mailable, User $user): void
    {
        if (! Setting::getBool($toggleKey, true)) {
            return;
        }

        if (empty($user->email)) {
            return;
        }

        try {
            Mail::to($user->email)->send($mailable);
        } catch (\Throwable $e) {
            Log::warning('SiteMail failed ['.$toggleKey.']: '.$e->getMessage());
        }
    }

    public static function toggles(): array
    {
        return [
            'email_welcome' => 'Welcome email on registration',
            'email_route_saved' => 'Thanks email when a route is saved',
            'email_restriction_reported' => 'Thanks email when a hazard is reported',
            'email_review' => 'Acknowledgement when a review is left',
            'email_donation' => 'Acknowledgement when a donation is made',
        ];
    }
}
