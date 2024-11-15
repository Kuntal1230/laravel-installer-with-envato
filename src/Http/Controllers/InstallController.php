<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Controllers;

use Gupta\LaravelInstallerWithEnvato\Facades\License;
use Gupta\LaravelInstallerWithEnvato\Http\Requests\StoreAgreementRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Jackiedo\DotenvEditor\DotenvEditor;
use ZipArchive;

class InstallController extends Controller
{
    protected $dotenvEditor;

    public function __construct(DotenvEditor $dotenvEditor)
    {
        $this->dotenvEditor = $dotenvEditor;
    }

    public function index()
    {
        Cache::clear();

        $path = base_path(config('installer.user_agreement_file_path'));
        if (File::isFile($path)) {
            $agreement = file_get_contents($path);
        } else {
            $agreement = file_get_contents(__DIR__ . '/../../../AGREEMENT.md');
        }

        return view('installer::index', [
            'agreement' => $agreement,
            'showAgreement' => config('installer.show_user_agreement'),
        ]);
    }

    public function store(StoreAgreementRequest $request)
    {
        if ($request->validated('agree')) {
            Cache::put('installer.agreement', true);

            return redirect()->route('installer.requirements.index');
        }
    }

    public function finish()
    {
        // Check if License is verified
        $verifyLicense = License::verify();
        if (! $verifyLicense['status']) {
            flash($verifyLicense['message'], 'error');

            return redirect()->route('installer.license.index');
        }

        try {
            $zip_file = base_path('public/install/installer.zip');
            if (file_exists($zip_file)) {
                $zip = new ZipArchive;
                if ($zip->open($zip_file) === TRUE) {
                    $zip->extractTo(base_path('/'));
                    $zip->close();
                } else {
                    return response()->json([
                        'error' => "Installation files Not Found, Please Try Again",
                        'route' => route('installer.agreement.index'),
                    ]);
                }
                unlink($zip_file);
            }

            $keys = [
                'APP_INSTALLED' => true,
                'APP_URL' => URL::to('/'),
                'ASSET_URL' => URL::to('/')
            ];
            $this->dotenvEditor->setKeys($keys)->save();
        } catch (\Exception $e) {
        }

        Storage::disk('local')->put('installed', now());

        Cache::clear();

        if (config('installer.extra.command')) {
            Artisan::call(config('installer.extra.command'));
        }

        return view('installer::finish');
    }
}
