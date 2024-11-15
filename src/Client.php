<?php

namespace Gupta\LaravelInstallerWithEnvato;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    private string|null $baseUrl;

    private string|null $apiKey;

    private string|null $language;

    public function __construct()
    {
        $this->baseUrl = config('installer.license.api_url');
        $this->apiKey = config('installer.license.api_key');
        $this->language = config('installer.license.api_language');
    }

    public function post($url, array $data = []): PromiseInterface|Response
    {
        return Http::withHeaders($this->getHeaders())->post($this->getUri($url), $data);
    }

    public function put($url, $data = []): PromiseInterface|Response
    {
        return Http::withHeaders($this->getHeaders())->put($this->getUri($url), $data);
    }

    public function get($url, $data = []): PromiseInterface|Response
    {
        return Http::withHeaders($this->getHeaders())->get($this->getUri($url), $data);
    }

    private function getUri($url): string
    {
        // Check if the url is start with "/"
        if (str_starts_with($url, '/')) {
            $url = substr($url, 1);
        }

        // Check if the base url is end with "/"
        if (str_ends_with($this->baseUrl, '/')) {
            $this->baseUrl = substr($this->baseUrl, 0, -1);
        }

        // Check the url is valid
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        // Check if the url is full url
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return $this->baseUrl . '/' . $url;
    }

    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'LB-API-KEY' => $this->apiKey,
            'LB-URL' => request()->getSchemeAndHttpHost(),
            'LB-IP' => request()->ip(),
            'LB-LANG' => $this->language,
        ];
    }
}
