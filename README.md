# S3DirectUploader Laravel Package

S3DirectUploader is a headless Laravel package designed to facilitate direct file uploads to Amazon S3 from the client/browser. Please note that users need to build their own File Input, although a complimentary component will be available in the near future. This package then handles the uploaded files, creates corresponding file records in your database, and offers various features such as automatic thumbnail generation, image resizing and scaling, MIME type restrictions, and more.

## Features

- Direct file uploads to Amazon S3 from the client/browser.
- Creation of file records in the database.
- Laravel auto-discovery support.
- S3DirectUploader Facade.
- Config file with sensible defaults.
- Route macro for convenience.
- Automatic thumbnail generation (can be disabled).
- Image resizing and scaling options.
- MIME type restrictions.
- File size restriction.
- Prefixing file names.
- Configurable disk via config/filesystems.php.

## Installation

You can install the S3DirectUploader package via Composer. Run the following command:

```bash
composer require stianscholtz/s3-direct-uploader
```

The package will automatically register its service provider and Facade.

## Configuration

After installing the package, you can update the config/s3-direct-uploader.php configuration file with your s3 disk, file model, default thumbnail dimensions, and other settings.

```
php artisan vendor:publish --provider="Stianscholtz\S3DirectUploader\UploaderServiceProvider" --tag=config
```

## Database Migration
S3DirectUploader provides a migration file to create the files table in your database. You do not need to publish this migration, simply run the following and the table will be created if it does not exist:

```
php artisan migrate
```

## Usage
### Route Macro
You can use the upload route macro for convenience or as an alternative to Route::match(['get', 'post']).

```
Route::upload('/upload', [UploadController::class, 'upload'])->name('file.upload');
```

instead of  

```
Route::match(['get', 'post'], '/upload', [UploadController::class, 'upload'])->name('file.upload');
```

### Controller

```
use S3DirectUploader;
use App\Models\Banner; // Assuming your model name is Banner

public function upload(Banner $banner): Model|array|File
{
    return S3DirectUploader::directory('directory/sub-directory/another-sub-directory')
        ->mimeType('application/pdf')
        ->mimeTypes('image/jpg', 'image/jpeg', 'image/png', 'image/webp')
        ->imagesOnly()
        ->scale(1200)
        ->resize(1200, 400)
        ->thumbnail(500, 500)
        ->thumbnail(false) // Disable thumbnail generation, if default dimesions are specified in config
        ->maxSize(15)
        ->unique(false)
        ->prefix('my-prefix-')
        ->disk('s3-public')// Use another s3 disk configured in config/filesystems.php
        ->after(function (File $file) use ($banner) {
            $banner->update(['file_id', $file->id]);
        })
        ->handle();
}
```

## Contributing
Contributions are welcome! Please feel free to submit a pull request.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.