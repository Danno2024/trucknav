<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    public function system()
    {
        $settings = [
            'app_name' => config('app.name', 'TruckNav'),
            'app_url' => config('app.url', ''),
            'maintenance_mode' => config('app.maintenance', false),
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
