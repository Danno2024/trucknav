<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (auth()->user()->role !== 'admin') {
            auth()->logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This account does not have admin access.',
            ]);
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This admin account has been deactivated.',
            ]);
        }

        return $next($request);
    }
}
