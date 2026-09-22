<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Setting::getBool('maintenance_mode')) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->isAdmin() && auth()->user()->is_active) {
            return $next($request);
        }

        if ($request->is('admin*') || $request->is('login') || $request->is('register')) {
            return $next($request);
        }

        $message = Setting::get('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 503);
        }

        return response()->view('maintenance', ['message' => $message], 503);
    }
}
