<?php

namespace Stianscholtz\S3DirectUploader;

use Aws\S3\PostObjectV4;
use Aws\S3\S3Client;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Uploader
{
    protected string $disk;
    protected string|null $directory;
    protected float|null $maxSize;
    protected bool $unique;
    protected string|null $prefix;
    protected int $signatureValidForMinutes;
    protected ?Closure $after = null;
    protected array|string $mimeTypes = [];
    protected Thumbnail $thumbnail;
    protected Resizer $resizer;
    protected ?string $contents = null;

    protected bool $createThumbnail;
    protected string $thumbnailMethod;
    protected ?int $thumbnailSize = null;
    protected ?int $thumbnailWidth = null;
    protected ?int $thumbnailHeight = null;

    public function __construct()
    {
        $this->disk = config('s3-direct-uploader.disk', 's3');
        $this->directory = config('s3-direct-uploader.directory');
        $this->maxSize = config('s3-direct-uploader.maxSize');
        $this->unique = (bool)config('s3-direct-uploader.unique', true);
        $this->prefix = config('s3-direct-uploader.prefix', '');
        $this->signatureValidForMinutes = config('s3-direct-uploader.signature.valid', 5);
        $this->thumbnail = new Thumbnail();
        $this->resizer = new Resizer();

        $this->createThumbnail = config('s3-direct-uploader.thumbnail.enabled', true);
        $this->thumbnailMethod = config('s3-direct-uploader.thumbnail.method', 'scale');
        $this->thumbnailSize = config('s3-direct-uploader.thumbnail.scale.size');
        $this->thumbnailWidth = config('s3-direct-uploader.thumbnail.resize.width');
        $this->thumbnailHeight = config('s3-direct-uploader.thumbnail.resize.height');
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;
        return $this;
    }

    public function directory(string $directory): static
    {
        $this->directory = $directory;
        return $this;
    }

    public function maxSize(float $size): static
    {
        $this->maxSize = $size;
        return $this;
    }

    public function unique($unique = true): static
    {
        $this->unique = $unique;
        return $this;
    }

    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function after(Closure $closure): static
    {
        $this->after = $closure;
        return $this;
    }

    public function scale(int $size): static
    {
        $this->resizer->size($size);
        return $this;
    }

    public function thumbnail(bool $create): static
    {
        $this->createThumbnail = $create;
        return $this;
    }

    public function thumbnailMethod(string $method): static
    {
        $this->thumbnail->method($method);

        return $this;
    }

    public function thumbnailSize(int $size): static
    {
        $this->thumbnail->size($size);

        return $this;
    }

    public function thumbnailWidth(int $width): static
    {
        $this->thumbnail->width($width);

        return $this;
    }

    public function thumbnailHeight(int $height): static
    {
        $this->thumbnail->height($height);

        return $this;
    }

    public function shouldCreateThumbnail(): bool
    {
        return $this->createThumbnail && ($this->thumbnailSize > 0 || $this->thumbnailWidth > 0 || $this->thumbnailHeight > 0);
    }

    public function resize(int $width, int $height): static
    {
        $this->resizer
            ->width($width)
            ->height($height);

        return $this;
    }

    public function mimeType($mimeTypes): static
    {
        $this->mimeTypes = is_array($mimeTypes) ? $mimeTypes : func_get_args();

        return $this;
    }

    public function mimeTypes($mimeTypes): static
    {
        return $this->mimeType(func_get_args());
    }

    public function imagesOnly(): static
    {
        return $this->mimeTypes('image/jpg', 'image/jpeg', 'image/png', 'image/webp');
    }

    /**
     * @throws Exception
     */
    public function handle(): Model|array|Uploader
    {
        return match (strtoupper(request()->method())) {
            'GET' => $this->getFormData(),
            'POST' => $this->createFile(),
            default => throw new MethodNotAllowedException(['get', 'post']),
        };
    }

    /**
     * @return array
     */
    private function getFormData(): array
    {
        $this->validateSignatureRequest();

        $s3Client = $this->getS3Client();

        $bucket = $this->getBucket();

        $options = $this->getOptions($bucket);

        $postObject = $this->getPostObject($s3Client, $bucket, $options);

        $formAttributes = $postObject->getFormAttributes();
        $formInputs = $postObject->getFormInputs();
        $formInputs['key'] = $this->getKey();

        return [
            'form_action' => $formAttributes['action'],
            'form_inputs' => $formInputs,
        ];
    }

    private function validateSignatureRequest(): void
    {
        request()->validate([
            'name' => 'required',
            'mimeType' => $this->getMimeTypeRules()
        ]);
    }

    private function getFileName(): string
    {
        $uniqueId = ($this->unique ? (uniqid() . '_') : '');

        return $this->prefix . $uniqueId . request('name');
    }

    private function getS3Client(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ]
        ]);
    }

    private function getBucket(): mixed
    {
        return config('filesystems.disks.' . $this->disk . '.bucket');
    }

    private function getOptions(string $bucket): array
    {
        $options = [
            ['bucket' => $bucket],
            ['starts-with', '$key', $this->directory],
            ['starts-with', '$Content-Type', request('mimeType')]
        ];

        if ($this->maxSize > 0) {
            $options[] = [
                "content-length-range", 0, ceil($this->maxSize * 1024 * 1024)
            ];
        }

        return $options;
    }

    private function getPostObject(S3Client $s3Client, string $bucket, array $options): PostObjectV4
    {
        $expires = '+' . $this->signatureValidForMinutes . ' minute';
        $formInputs = [
            'Content-Type' => request('mimeType')
        ];

        return new PostObjectV4(
            $s3Client,
            $bucket,
            $formInputs,
            $options,
            $expires
        );
    }

    private function getKey(): string
    {
        $fileName = $this->getFileName();

        return ltrim($this->directory . (str_ends_with($this->directory, '/') ? '' : '/') . $fileName, '/');
    }

    private function getTemporaryURL(): string
    {
        $key = $this->getKey();

        return Storage::disk($this->disk)->temporaryUrl($key, now()->addMinutes($this->signatureValidForMinutes));
    }

    /**
     * @throws Exception
     */
    private function createFile(): Model|Uploader
    {
        $data = $this->validateCreateFileRequest();
        $pathInfo = pathinfo($data['name']);
        $fileData = [
            'name' => $data['name'],
            'extension' => strtolower($pathInfo['extension']),
            'size' => $data['size'],
            'path' => $data['path'],
            'mime_type' => $data['mimeType']
        ];

        if ($this->isImage($fileData['extension'])) {
            if ($this->resizer->shouldResize()) {
                $resizedImagePath = rtrim($fileData['path'], '.' . $fileData['extension']) . '.webp';
                $this->contents = Storage::disk($this->disk)->get($fileData['path']);
                $resizedImage = $this->resizer
                    ->contents($this->contents)
                    ->resize();

                Storage::disk($this->disk)->put($resizedImagePath, $resizedImage->toWebp());

                if ($fileData['path'] !== $resizedImagePath) {
                    Storage::disk($this->disk)->delete($fileData['path']);
                    $fileData['path'] = $resizedImagePath;
                }

                $fileData['extension'] = 'webp';
                $fileData['mime_type'] = 'image/webp';
            }

            if ($this->shouldCreateThumbnail()) {
                $thumbnailPath = rtrim($fileData['path'], '.' . $fileData['extension']) . '_t.webp';
                $this->contents = $this->contents ?? Storage::disk($this->disk)->get($fileData['path']);
                $thumbnail = $this->thumbnail
                    ->contents($this->contents)
                    ->method($this->thumbnailMethod)
                    ->size($this->thumbnailSize)
                    ->width($this->thumbnailWidth)
                    ->height($this->thumbnailHeight)
                    ->create();

                Storage::disk($this->disk)->put($thumbnailPath, $thumbnail->toWebp());
                $fileData['thumbnail_path'] = $thumbnailPath;
                $fileData['thumbnail_url'] = Storage::disk($this->disk)->url($fileData['thumbnail_path']);
            }
        }

        $fileData['url'] = Storage::disk($this->disk)->url($fileData['path']);

        $fileModel = config('s3-direct-uploader.model');

        if (!is_subclass_of($fileModel, Model::class)) {
            throw new Exception('Model not configured');
        }

        $file = $fileModel::create($fileData);

        $this->callAfter($file);

        return $file;
    }

    private function validateCreateFileRequest(): array
    {
        return request()->validate([
            'name' => 'required',
            'size' => 'required',
            'path' => 'required',
            'mimeType' => $this->getMimeTypeRules()
        ]);
    }

    private function isImage(?string $extension): bool
    {
        return in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'webp']);
    }

    private function getMimeTypeRules(): string
    {
        $rules = 'required';
        return count($this->mimeTypes) > 0 ? $rules . '|in:' . implode(',', $this->mimeTypes) : $rules;
    }

    private function callAfter(Model $file): void
    {
        is_callable($this->after) && call_user_func($this->after, $file);
    }
}
