<div class="filament-hidden">

![Laravel Pixel](https://raw.githubusercontent.com/jeffersongoncalves/laravel-pixel/master/art/jeffersongoncalves-laravel-pixel.png)

</div>

# Laravel Pixel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-pixel.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-pixel)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-pixel/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-pixel/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-pixel.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-pixel)

This Laravel package offers a straightforward way to integrate Meta (Facebook) Pixel into your application. By configuring your Pixel ID, you can effortlessly track user interactions, page views, conversions, and other valuable events on your website. This helps you gather insights into your audience behavior and optimize your advertising campaigns. The package simplifies the process of adding the Pixel script to your Blade templates, enabling seamless analytics collection with minimal setup.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-pixel
```

## Usage

Publish config file.

```bash
php artisan vendor:publish --tag=pixel-config
```

Add head template.

```php
@include('pixel::script')
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
