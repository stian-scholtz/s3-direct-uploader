<?php

namespace Stianscholtz\S3DirectUploader;

use Intervention\Image\Interfaces\ImageInterface;

class Thumbnail
{
    protected string $method;
    protected ?int $size = null;
    protected ?int $width = null;
    protected ?int $height = null;
    protected string $contents;

    public function contents(string $contents): static
    {
        $this->contents = $contents;
        return $this;
    }

    public function method(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function size(?int $size): static
    {
        $this->size = $size;
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
        if($this->method === 'scale' && $this->size > 0) {
            return (new Resizer)->contents($this->contents)
                ->size($this->size)
                ->resize();
        }
        elseif($this->method === 'resize' && ($this->width > 0 || $this->height > 0)){
            return (new Resizer)->contents($this->contents)
                ->width($this->width)
                ->height($this->height)
                ->resize();
        }
    }
}