<?php

namespace Gupta\LaravelInstallerWithEnvato\Facades;

use Illuminate\Support\Facades\Facade;
use Gupta\LaravelInstallerWithEnvato\License as LaravelInstallerWithEnvatoLicense;

/**
 * @see \Gupta\LaravelInstallerWithEnvato\License
 */
class License extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelInstallerWithEnvatoLicense::class;
    }
}
