<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class RequirementController extends Controller
{
    private string $minPhpVersion;

    public function __construct()
    {
        $this->minPhpVersion = config('installer.core.minPhpVersion');
    }

    public function index()
    {
        if (! Cache::get('installer.agreement')) {
            return redirect()->route('installer.agreement.index')->with('error', 'Please agree to the terms and conditions before proceeding.');
        }

        $phpSupportInfo = $this->checkPHPVersion();

        $requirements = $this->checkServerRequirements();

        $hasError = ! $phpSupportInfo['supported'] || ($requirements['errors'] ?? false);

        return view('installer::requirements', [
            'phpSupportInfo' => $phpSupportInfo,
            'requirements' => $requirements,
            'hasError' => $hasError,
        ]);
    }

    public function store()
    {
        if (! Cache::get('installer.agreement')) {
            return redirect()->route('installer.agreement.index')->with('error', 'Please agree to the terms and conditions before proceeding.');
        }

        $phpSupportInfo = $this->checkPHPVersion();

        $requirements = $this->checkServerRequirements();

        $hasError = ! $phpSupportInfo['supported'] || ($requirements['errors'] ?? false);

        if ($hasError) {
            return redirect()->route('installer.requirements.index')->with('error', 'Please fix the errors before proceeding.');
        }

        Cache::put('installer.requirements', true);

        return redirect()->route('installer.permissions.index');
    }

    /**
     * Check PHP version requirement.
     */
    private function checkPHPVersion(): array
    {
        $minVersionPhp = $this->getMinPhpVersion();
        $currentPhpVersion = $this->getPhpVersionInfo();
        $supported = false;

        if (version_compare($currentPhpVersion['version'], $minVersionPhp) >= 0) {
            $supported = true;
        }

        return [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $minVersionPhp,
            'supported' => $supported,
        ];
    }

    /**
     * Get current Php version information.
     */
    private static function getPhpVersionInfo(): array
    {
        $currentVersionFull = PHP_VERSION;
        preg_match("#^\d+(\.\d+)*#", $currentVersionFull, $filtered);
        $currentVersion = $filtered[0];

        return [
            'full' => $currentVersionFull,
            'version' => $currentVersion,
        ];
    }

    /**
     * Get minimum PHP version ID.
     *
     * @return string minPhpVersion
     */
    protected function getMinPhpVersion(): string
    {
        return $this->minPhpVersion;
    }

    private function checkServerRequirements(): array
    {
        $results = [];

        foreach (config('installer.requirements') as $type => $requirement) {
            switch ($type) {
                    // check php requirements
                case 'php':
                    foreach ($requirement as $php) {
                        $results['requirements'][$type][$php] = true;

                        if (! extension_loaded($php)) {
                            $results['requirements'][$type][$php] = false;

                            $results['errors'] = true;
                        }
                    }

                    break;
                    // check apache requirements
                    /*case 'apache':
                    foreach ($requirement as $apache) {
                        // if function doesn't exist we can't check apache modules
                        if (function_exists('apache_get_modules')) {
                            $results['requirements'][$type][$apache] = true;

                            if (! in_array($requirement, apache_get_modules())) {
                                $results['requirements'][$type][$apache] = false;

                                $results['errors'] = true;
                            }
                        }
                    }

                    break;
                // check litespeed requirements
                case 'litespeed':
                    foreach ($requirement as $litespeed) {
                        // if function doesn't exist we can't check litespeed modules
                        if (function_exists('apache_get_modules')) {
                            $results['requirements'][$type][$litespeed] = true;

                            if (! in_array($requirement, apache_get_modules())) {
                                $results['requirements'][$type][$litespeed] = false;

                                $results['errors'] = true;
                            }
                        }
                    }

                    break;*/
            }
        }

        return $results;
    }
}
