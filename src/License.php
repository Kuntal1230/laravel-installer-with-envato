<?php

namespace Gupta\LaravelInstallerWithEnvato;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class License
{
    private Client $client;
    private $script;
    private $platform;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->script = config('installer.license.script');
        $this->platform = config('installer.license.platform');
    }

    public function activate($purchaseCode, $clientName): PromiseInterface|Response
    {
        $response = $this->client->post('/api/license-verification', [
            'domain' => urlencode($_SERVER['SERVER_NAME']),
            'script' => $this->script,
            'platform' => $this->platform,
            'username' => $clientName,
            'purchase_code' => $purchaseCode,
        ]);

        if ($response->successful() && $response->json('status')) {
            $this->saveLicense($response->json('lic_response'));
            $this->saveZipfile($response->json('release_zip_link'));
        } else {
            $this->removeLicense();
        }

        return $response;
    }

    public function verify($purchaseCode = null, $clientName = null, bool $timeBased = false): array
    {
        $localLicenseFile = $this->getLicenseFile();

        if ($localLicenseFile) {
            $licenseData = json_encode($localLicenseFile, true);

            return [
                'status' => true,
                'message' => 'License verified successfully.',
                'data' => $licenseData,
            ];
        } else {
            return [
                'status' => false,
                'message' => 'License verification failed.',
                'data' => null,
            ];
        }
    }

    private function saveLicense(array $data): void
    {
        // Read the contents of agreement.md
        $agreementPath = base_path('vendor/Gupta/laravel-installer-with-envato/agreement.md');
        $agreementContent = file_get_contents($agreementPath);

        // Write the contents to the license file
        $licensePath = base_path('vendor/Gupta/laravel-installer-with-envato/license');
        file_put_contents($licensePath, $agreementContent, LOCK_EX);
    }

    private function removeLicense(): void
    {
        if (file_exists(base_path('vendor/Gupta/laravel-installer-with-envato/license'))) {
            if (!is_writable(base_path('vendor/Gupta/laravel-installer-with-envato/license'))) {
                @chmod(base_path('vendor/Gupta/laravel-installer-with-envato/license'), 0777);
            }

            unlink(base_path('vendor/Gupta/laravel-installer-with-envato/license'));
        }
    }

    private function getLicenseFile(): bool|string|null
    {
        $path = base_path('vendor/Gupta/laravel-installer-with-envato/license');

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return null;
    }

    private function saveZipfile($zip_file): bool|string|null
    {
        try {
            $file_path = base_path('public/install/installer.zip');
            file_put_contents($file_path, file_get_contents($zip_file));
            return true;
        } catch (Exception $e) {
            return 'Zip file cannot be Imported. Please check your server permission or Contact with Script Author.';
        }
    }
}
