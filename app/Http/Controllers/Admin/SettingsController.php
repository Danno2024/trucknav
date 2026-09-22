<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

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
}
