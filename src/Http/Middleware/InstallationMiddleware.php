<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class InstallationMiddleware
{
    /**
     * Check if the installation is complete
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Storage::disk('local')->exists('installed') && ! config('app.app_installed')) {
            return redirect()->route('installer.agreement.index');
        }

        return $next($request);
    }
}
