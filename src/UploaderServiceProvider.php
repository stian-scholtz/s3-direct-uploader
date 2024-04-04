<?php

namespace Stianscholtz\S3DirectUploader;

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UploaderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('s3-direct-uploader')
            ->hasConfigFile('s3-direct-uploader')
            ->hasMigration('create_files_table');
    }

    public function packageRegistered(): void
    {
        if (!$this->app->resolved('s3-direct-uploader')) {
            $this->app->singleton('s3-direct-uploader', function () {
                return new Uploader();
            });
        }
    }

    public function packageBooted(): void
    {
        if (!Route::hasMacro('upload')) {
            Route::macro('upload', fn(string $uri, array $action) => Route::match(['get', 'post'], $uri, $action));
        }
    }
}
