<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Controllers;

use Gupta\LaravelInstallerWithEnvato\Facades\License;
use Gupta\LaravelInstallerWithEnvato\Http\Requests\StoreLicenseRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    public function index()
    {
        if (! Cache::get('installer.agreement')) {
            flash('Please agree to the terms and conditions.', 'error');

            return redirect()->route('installer.agreement.index');
        }

        if (! Cache::get('installer.requirements')) {
            flash('Please check the requirements.', 'error');

            return redirect()->route('installer.requirements.index');
        }

        if (! Cache::get('installer.permissions')) {
            flash('Please check the permissions.', 'error');

            return redirect()->route('installer.permissions.index');
        }

        return view('installer::license');
    }

    public function store(StoreLicenseRequest $request)
    {
        $response = License::activate($request->validated('purchase_code'), $request->validated('envato_username'));

        if ($response['status']) {
            Cache::put('installer.license', true);

            return response()->json([
                'status' => 'success',
                'message' => $response['message'],
                'redirect' => route('installer.database.index'),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $response['message'],
            ], 422);
        }
    }

    public function activation()
    {
        if (! Storage::disk('local')->exists('installed') || ! config('app.app_installed')) {
            flash('Please install the application.', 'error');

            return redirect()->route('installer.agreement.index');
        }

        return view('installer::activation');
    }

    public function activate(StoreLicenseRequest $request)
    {
        $response = License::activate($request->validated('purchase_code'), $request->validated('envato_username'));

        if ($response->json('status')) {
            return response()->json([
                'status' => 'success',
                'message' => $response->json('message'),
                'redirect' => session('url.intended', route('login')),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $response->json('message'),
            ], 422);
        }
    }
}
