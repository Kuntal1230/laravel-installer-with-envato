<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Gupta\LaravelInstallerWithEnvato\Facades\License;

class LicensedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Storage::disk('local')->exists('installed')) {
            $verifyLicense = License::verify();

            if (! $verifyLicense['status']) {
                flash($verifyLicense['message'], 'error');

                return redirect()->route('installer.license.activation');
            }
        }
        if (!config('app.app_installed')) {
            return redirect()->route('installer.license.activation');
        }

        return $next($request);
    }
}
