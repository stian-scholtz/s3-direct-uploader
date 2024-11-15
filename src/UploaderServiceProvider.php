<?php

namespace Stianscholtz\S3DirectUploader;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UploaderServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind('s3-direct-uploader', function () {
            return new Uploader();
        });

        Route::macro('upload', fn(string $uri, array $action) => Route::match(['get', 'post'], $uri, $action));
    }

    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/s3-direct-uploader.php' => config_path('s3-direct-uploader.php'),
            ], 'config');
        }

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');
    }
}
