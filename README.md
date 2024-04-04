# A Laravel Package to upload files to s3 directly from the client.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stian-scholtz/file-uploader.svg?style=flat-square)](https://packagist.org/packages/stian-scholtz/file-uploader)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/stian-scholtz/file-uploader/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stian-scholtz/file-uploader/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/stian-scholtz/file-uploader/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/stian-scholtz/file-uploader/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/stian-scholtz/file-uploader.svg?style=flat-square)](https://packagist.org/packages/stian-scholtz/file-uploader)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/file-uploader.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/file-uploader)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require stian-scholtz/file-uploader
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="file-uploader-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="file-uploader-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="file-uploader-views"
```

## Usage

```php
$fileUploader = new Stianscholtz\FileUploader();
echo $fileUploader->echoPhrase('Hello, Stianscholtz!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Stian Scholtz](https://github.com/stian-scholtz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
