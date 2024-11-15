<?php

namespace Gupta\LaravelInstallerWithEnvato\Facades;

use Illuminate\Support\Facades\Facade;
use Gupta\LaravelInstallerWithEnvato\Installer as LaravelInstallerWithEnvatoInstaller;

/**
 * @see \Gupta\LaravelInstallerWithEnvato\Installer
 */
class Installer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelInstallerWithEnvatoInstaller::class;
    }
}
