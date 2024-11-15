<?php

use Gupta\LaravelInstallerWithEnvato\Http\Controllers\AdminController;
use Gupta\LaravelInstallerWithEnvato\Http\Controllers\DatabaseController;
use Gupta\LaravelInstallerWithEnvato\Http\Controllers\InstallController;
use Gupta\LaravelInstallerWithEnvato\Http\Controllers\LicenseController;
use Gupta\LaravelInstallerWithEnvato\Http\Controllers\PermissionController;
use Gupta\LaravelInstallerWithEnvato\Http\Controllers\RequirementController;
use Gupta\LaravelInstallerWithEnvato\Http\Controllers\SMTPController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('installer.prefix', 'installer'),
    'as' => 'installer.',
    'middleware' => ['web', 'installed'],
    'excluded_middleware' => ['install', 'licensed'],
], function () {
    Route::get('/', [InstallController::class, 'index'])->name('agreement.index');
    Route::post('/', [InstallController::class, 'store'])->name('agreement.store');

    Route::get('requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::post('requirements', [RequirementController::class, 'store'])->name('requirements.store');

    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');

    Route::get('license', [LicenseController::class, 'index'])->name('license.index');
    Route::post('license', [LicenseController::class, 'store'])->name('license.store');

    Route::get('database', [DatabaseController::class, 'index'])->name('database.index');
    Route::post('database', [DatabaseController::class, 'store'])->name('database.store');

    Route::get('smtp', [SMTPController::class, 'index'])->name('smtp.index');
    Route::post('smtp', [SMTPController::class, 'store'])->name('smtp.store');

    Route::get('admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('admin', [AdminController::class, 'store'])->name('admin.store');

    Route::get('finish', [InstallController::class, 'finish'])->name('finish.index');

    Route::get('activation', [LicenseController::class, 'activation'])->name('license.activation')->withoutMiddleware('installed');
    Route::post('activation', [LicenseController::class, 'activate'])->name('license.activation.activate')->withoutMiddleware('installed');
});
