<?php

namespace Stianscholtz\S3DirectUploader\Facades;

use Illuminate\Support\Facades\Facade;
use Stianscholtz\S3DirectUploader\Uploader;

/**
 * @see Uploader
 */
class S3DirectUploader extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Uploader::class;
    }
}
