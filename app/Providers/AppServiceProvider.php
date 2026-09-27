<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('auth.*', function ($view) {
            $view->with('maintenanceMode', Setting::getBool('maintenance_mode'));
            $view->with('maintenanceMessage', Setting::get('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.'));
        });

        $this->applyMailSettings();
    }

    /**
     * Override mail config from admin Email Settings (DB) so SMTP
     * can be managed without editing .env. Falls back to .env
     * values for anything not configured.
     */
    protected function applyMailSettings(): void
    {
        $mailer = Setting::get('mail_mailer');

        if (empty($mailer)) {
            return;
        }

        config(['mail.default' => $mailer]);

        if ($host = Setting::get('mail_host')) {
            config(['mail.mailers.smtp.host' => $host]);
        }

        if ($port = Setting::get('mail_port')) {
            config(['mail.mailers.smtp.port' => (int) $port]);
        }

        if ($username = Setting::get('mail_username')) {
            config(['mail.mailers.smtp.username' => $username]);
        }

        $encrypted = Setting::get('mail_password');
        if (! empty($encrypted)) {
            try {
                config(['mail.mailers.smtp.password' => decrypt($encrypted)]);
            } catch (\Throwable $e) {
                config(['mail.mailers.smtp.password' => null]);
            }
        }

        $encryption = Setting::get('mail_encryption');
        if ($encryption !== null && $encryption !== '') {
            config(['mail.mailers.smtp.encryption' => $encryption]);
        } elseif ($encryption === '') {
            config(['mail.mailers.smtp.encryption' => null]);
        }

        if ($fromAddress = Setting::get('mail_from_address')) {
            config(['mail.from.address' => $fromAddress]);
        }

        if ($fromName = Setting::get('mail_from_name')) {
            config(['mail.from.name' => $fromName]);
        }
    }
}
