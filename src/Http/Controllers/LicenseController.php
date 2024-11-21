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
            return redirect()->route('installer.agreement.index')->with('error', 'Please agree to the terms and conditions.');
        }

        if (! Cache::get('installer.requirements')) {
            return redirect()->route('installer.requirements.index')->with('error', 'Please check the requirements.');
        }

        if (! Cache::get('installer.permissions')) {
            return redirect()->route('installer.permissions.index')->with('error', 'Please check the permissions.');
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

            return redirect()->route('installer.agreement.index')->with('error', 'Please install the application.');
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
