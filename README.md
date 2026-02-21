<div class="filament-hidden">

![Laravel Pixel](https://raw.githubusercontent.com/jeffersongoncalves/laravel-pixel/master/art/jeffersongoncalves-laravel-pixel.png)

</div>

# Laravel Pixel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-pixel.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-pixel)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-pixel/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-pixel/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-pixel.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-pixel)

This Laravel package offers a straightforward way to integrate Meta (Facebook) Pixel into your application. The Pixel ID is stored in the database using [spatie/laravel-settings](https://github.com/spatie/laravel-settings), allowing you to manage it dynamically (e.g., via an admin panel) without relying on `.env` files or static config.

## Installation

Install the package via composer:

```bash
composer require jeffersongoncalves/laravel-pixel
```

Publish and run the spatie/laravel-settings migrations (if not already done):

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan migrate
```

Publish and run the pixel settings migration:

```bash
php artisan vendor:publish --tag="pixel-settings-migrations"
php artisan migrate
```

## Usage

### Setting the Pixel ID

You can set the Pixel ID programmatically using any of these methods:

**Helper function:**

```php
$settings = pixel_settings();
$settings->pixel_id = '123456789';
$settings->save();
```

**Facade:**

```php
use JeffersonGoncalves\Pixel\Facades\PixelSettings;

$settings = PixelSettings::getFacadeRoot();
$settings->pixel_id = '123456789';
$settings->save();
```

**Container resolution:**

```php
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

$settings = app(PixelSettings::class);
$settings->pixel_id = '123456789';
$settings->save();
```

### Adding the Pixel script to your layout

Include the Blade component in your layout's `<head>` tag:

```blade
@include('pixel::script')
```

The script will only render when a valid Pixel ID is configured. If the Pixel ID is `null` or empty, nothing will be rendered.

### Reading the Pixel ID

```php
$pixelId = pixel_settings()->pixel_id; // returns string|null
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jèfferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
