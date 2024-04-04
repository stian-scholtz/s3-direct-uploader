<?php

namespace Stianscholtz\S3DirectUploader;

use Intervention\Image\Interfaces\ImageInterface;

class Thumbnail
{
    protected string $disk;
    protected ?int $width = null;
    protected ?int $height = null;
    protected string $contents;

    public function __construct()
    {
        $this->disk = config('s3-direct-uploader..disk', 's3');
        $this->width = config('s3-direct-uploader.thumbnail.width');
        $this->height = config('s3-direct-uploader.thumbnail.height');
    }

    public function contents(string $contents): static
    {
        $this->contents = $contents;
        return $this;
    }

    public function width(?int $width): static
    {
        $this->width = $width;
        return $this;
    }

    public function height(?int $height): static
    {
        $this->height = $height;
        return $this;
    }

    public function create(): ImageInterface|bool
    {
        if($this->width > 0 || $this->height > 0){
            return (new Resizer)->contents($this->contents)
                ->width($this->width)
                ->height($this->height)
                ->resize();
        }
    }

    public function shouldCreate(): bool
    {
        return $this->width > 0 || $this->height > 0;
    }
}