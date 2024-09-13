<?php

namespace Stianscholtz\S3DirectUploader;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class Resizer
{
    protected ?int $width = null;
    protected ?int $height = null;
    protected ?int $size = null;
    protected string $contents;

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

    public function size(int $size): static
    {
        $this->size = $size;
        return $this;
    }

    public function resize(): bool|ImageInterface
    {
        if (!$this->shouldResize()) {
            return false;
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->contents);

        if ($this->size > 0) {
            return $this->scaleDown($image);
        } elseif ($this->width > 0 || $this->height > 0) {
            return $this->resizeDown($image);
        }

        throw new \LogicException();
    }

    public function shouldResize(): bool
    {
        return $this->size > 0 || $this->width > 0 || $this->height > 0;
    }

    private function scaleDown(ImageInterface $image): ImageInterface
    {
        $width = $image->width();
        $height = $image->height();

        if ($width >= $height && $width > $this->size) {
            $image->scaleDown(width: $this->size);
        } elseif ($height > $width && $height > $this->size) {
            $image->scaleDown(height: $this->size);
        }

        return $image;
    }

    private function resizeDown(ImageInterface $image): ImageInterface
    {
        $image->resizeDown($this->width, $this->height);

        return $image;
    }
}