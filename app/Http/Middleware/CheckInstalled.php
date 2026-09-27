<?php

namespace App\Http\Middleware;

use App\Support\Installer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        $installed = Installer::isInstalled();
        $isInstallRoute = $request->is('install*');

        if (! $installed && ! $isInstallRoute && ! $request->is('up')) {
            return redirect()->route('install.welcome');
        }

        if ($installed && $isInstallRoute) {
            return redirect('/');
        }

        return $next($request);
    }
}
