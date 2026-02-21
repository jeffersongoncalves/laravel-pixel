# Changelog

All notable changes to this project will be documented in this file.

## 2.0.0 - 2026-02-20

### Breaking Changes

- **Removed** `config/pixel.php` and `env('PIXEL_ID')` — Pixel ID is now stored in the database via [spatie/laravel-settings](https://github.com/spatie/laravel-settings)
- Users must publish and run migrations after upgrading

### What's New

- **Dynamic Pixel ID management** — configure via database instead of static `.env` values (ideal for admin panels)
- **PixelSettings class** — `pixel_settings()->pixel_id` to read/write the Pixel ID
- **Facade** — `PixelSettings` facade for convenient access
- **Helper function** — `pixel_settings()` global helper
- **Settings migration** — `pixel.pixel_id` stored in the `settings` table
- **11 Pest tests** — full coverage for settings, Blade rendering, helper, facade, and container resolution
- **PHPUnit 11 compatibility** — updated `phpunit.xml.dist`

### Upgrade Guide

1. Update the package:
   
   ```bash
   composer update jeffersongoncalves/laravel-pixel
   
   ```
2. Publish and run spatie/laravel-settings migrations (if not already done):
   
   ```bash
   php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
   php artisan migrate
   
   ```
3. Publish and run pixel settings migration:
   
   ```bash
   php artisan vendor:publish --tag="pixel-settings-migrations"
   php artisan migrate
   
   ```
4. Set your Pixel ID in the database:
   
   ```php
   pixel_settings()->pixel_id = 'YOUR_PIXEL_ID';
   pixel_settings()->save();
   
   ```
5. Remove `PIXEL_ID` from your `.env` file and delete `config/pixel.php` if published.
   

## 1.0.0 - 2025-05-01

**Full Changelog**: https://github.com/jeffersongoncalves/laravel-pixel/commits/1.0.0
