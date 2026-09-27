<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\SiteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function system()
    {
        $settings = [
            'app_name' => config('app.name', 'TruckRoute'),
            'app_url' => config('app.url', ''),
            'maintenance_mode' => Setting::getBool('maintenance_mode'),
            'maintenance_message' => Setting::get('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.'),
        ];

        return view('admin.settings.system', compact('settings'));
    }

    public function updateSystem(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
        ]);

        config(['app.name' => $request->app_name]);

        Setting::set('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0');
        Setting::set('maintenance_message', $request->input('maintenance_message', ''));

        return back()->with('success', 'System settings updated.');
    }

    public function paypal()
    {
        $settings = [
            'business_email' => config('services.paypal.business_email', ''),
        ];

        return view('admin.settings.paypal', compact('settings'));
    }

    public function updatePaypal(Request $request)
    {
        $request->validate([
            'business_email' => 'required|email',
        ]);

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        if (preg_match('/^PAYPAL_BUSINESS_EMAIL=.*/m', $envContent)) {
            $envContent = preg_replace('/^PAYPAL_BUSINESS_EMAIL=.*/m', "PAYPAL_BUSINESS_EMAIL={$request->business_email}", $envContent);
        } else {
            $envContent .= "\nPAYPAL_BUSINESS_EMAIL={$request->business_email}\n";
        }

        file_put_contents($envPath, $envContent);

        return back()->with('success', 'PayPal settings updated.');
    }

    public function email()
    {
        $settings = [
            'mail_mailer' => Setting::get('mail_mailer', ''),
            'mail_host' => Setting::get('mail_host', ''),
            'mail_port' => Setting::get('mail_port', '587'),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_password_set' => (bool) Setting::get('mail_password'),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
            'mail_from_address' => Setting::get('mail_from_address', ''),
            'mail_from_name' => Setting::get('mail_from_name', ''),
        ];

        foreach (array_keys(SiteMail::toggles()) as $key) {
            $settings[$key] = Setting::getBool($key, true);
        }

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'nullable|in:smtp,log',
            'mail_host' => 'required_if:mail_mailer,smtp|nullable|string|max:255',
            'mail_port' => 'required_if:mail_mailer,smtp|nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|in:tls,ssl,none',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        Setting::set('mail_mailer', $request->input('mail_mailer', ''));
        Setting::set('mail_host', $request->input('mail_host', ''));
        Setting::set('mail_port', $request->input('mail_port', '587'));
        Setting::set('mail_username', $request->input('mail_username', ''));

        if ($request->filled('mail_password')) {
            Setting::set('mail_password', encrypt($request->input('mail_password')));
        }

        $encryption = $request->input('mail_encryption', 'tls');
        Setting::set('mail_encryption', $encryption === 'none' ? '' : $encryption);

        Setting::set('mail_from_address', $request->input('mail_from_address', ''));
        Setting::set('mail_from_name', $request->input('mail_from_name', ''));

        foreach (array_keys(SiteMail::toggles()) as $key) {
            Setting::set($key, $request->boolean($key) ? '1' : '0');
        }

        return back()->with('success', 'Email settings updated.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_address' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from '.config('app.name').'. If you received this, email sending is configured correctly.', function ($message) use ($request) {
                $message->to($request->input('test_address'))
                    ->subject('Test email from '.config('app.name'));
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['test_address' => 'Failed to send: '.$e->getMessage()]);
        }

        return back()->with('success', 'Test email sent to '.$request->input('test_address').'.');
    }
}
