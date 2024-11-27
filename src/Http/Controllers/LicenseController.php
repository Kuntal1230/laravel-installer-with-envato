<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Controllers;

use Gupta\LaravelInstallerWithEnvato\Facades\License;
use Gupta\LaravelInstallerWithEnvato\Http\Requests\StoreLicenseRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    public function index()
    {
        return view('installer::license');
    }

    public function store(StoreLicenseRequest $request)
    {
        $response = License::activate($request->validated('purchase_code'), $request->validated('envato_username'));

        if ($response['status']) {

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
