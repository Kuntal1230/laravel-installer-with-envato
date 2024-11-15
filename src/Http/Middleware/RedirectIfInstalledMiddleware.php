<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RedirectIfInstalledMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Storage::disk('local')->exists('installed') && config('app.app_installed')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
