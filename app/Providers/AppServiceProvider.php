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
    }
}
