<?php

namespace Stianscholtz\S3DirectUploader;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UploaderServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/s3-direct-uploader.php',
            's3-direct-uploader'
        );

        $this->app->bind('s3-direct-uploader', function () {
            return new Uploader();
        });

        Route::macro('upload', fn(string $uri, array $action) => Route::match(['get', 'post'], $uri, $action));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/s3-direct-uploader.php' => config_path('s3-direct-uploader.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
