---
name: pixel-development
description: Development guide for the laravel-pixel package -- Meta (Facebook) Pixel integration for Laravel using spatie/laravel-settings.
---

# Pixel Development Skill

## When to use this skill

- When developing or modifying the `jeffersongoncalves/laravel-pixel` package.
- When adding new settings properties to `PixelSettings`.
- When modifying the Blade tracking script.
- When writing tests for the package.
- When integrating Meta (Facebook) Pixel into a Laravel application.

## Setup

### Requirements

- PHP 8.2 or 8.3
- Laravel 11, 12, or 13
- `spatie/laravel-settings` ^3.3
- `spatie/laravel-package-tools` ^1.14.0

### Installation

```bash
composer require jeffersongoncalves/laravel-pixel
```

### Publish and run the settings migration

```bash
php artisan vendor:publish --tag="pixel-settings-migrations"
php artisan migrate
```

### Include the tracking script in your layout

```blade
<head>
    @include('pixel::script')
</head>
```

## Architecture

### Directory Structure

```
src/
  PixelServiceProvider.php          # Package service provider
  Settings/
    PixelSettings.php               # Spatie Settings class (group: pixel)
  Facades/
    PixelSettings.php               # Laravel Facade for PixelSettings
  helpers.php                       # Global pixel_settings() helper function
database/
  settings/
    2026_02_20_000000_create_pixel_settings.php  # Settings migration
resources/
  views/
    script.blade.php                # Facebook Pixel tracking script Blade view
```

### Service Provider

`PixelServiceProvider` extends `Spatie\LaravelPackageTools\PackageServiceProvider`:

- Registers the package name as `laravel-pixel` with views.
- Auto-registers `PixelSettings` into the `settings.settings` config array.
- Loads migrations from the `database/settings` directory.
- Publishes settings migrations under tag `pixel-settings-migrations`.

```php
class PixelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-pixel')
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        /** @var array<int, class-string> $settings */
        $settings = Config::get('settings.settings', []);
        $settings[] = PixelSettings::class;

        Config::set('settings.settings', $settings);
    }

    public function packageBooted(): void
    {
        $migrationPath = __DIR__.'/../database/settings';

        $this->loadMigrationsFrom($migrationPath);

        $this->publishes([
            $migrationPath => database_path('settings'),
        ], 'pixel-settings-migrations');
    }
}
```

### Settings Class

`PixelSettings` uses `spatie/laravel-settings` with group `pixel`:

```php
use Spatie\LaravelSettings\Settings;

class PixelSettings extends Settings
{
    public ?string $pixel_id = null;

    public static function group(): string
    {
        return 'pixel';
    }
}
```

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `pixel_id` | `?string` | `null` | Meta (Facebook) Pixel ID |

### Facade

The `PixelSettings` facade resolves to the `PixelSettings` settings class:

```php
namespace JeffersonGoncalves\Pixel\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\Pixel\Settings\PixelSettings as PixelSettingsClass;

class PixelSettings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PixelSettingsClass::class;
    }
}
```

The facade alias `PixelSettings` is auto-registered via `composer.json`.

### Helper Function

The `pixel_settings()` helper is autoloaded via `composer.json` `files` directive:

```php
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

if (! function_exists('pixel_settings')) {
    function pixel_settings(): PixelSettings
    {
        return app(PixelSettings::class);
    }
}
```

### Blade View

The `script.blade.php` view uses the `pixel_settings()` helper and conditionally renders the Facebook Pixel:

```blade
@if(!empty(pixel_settings()->pixel_id))
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ pixel_settings()->pixel_id }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
             src="https://www.facebook.com/tr?id={{ pixel_settings()->pixel_id }}&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@endif
```

Key behaviors:
- Only renders when `pixel_id` is not null/empty.
- Loads the `fbevents.js` SDK from `connect.facebook.net`.
- Calls `fbq('init', pixelId)` followed by `fbq('track', 'PageView')`.
- Includes a `<noscript>` fallback image for users without JavaScript.
- Uses the `pixel_settings()` helper (not `app()`) for cleaner access.

## Features

### Reading Settings

```php
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

// Via container
$settings = app(PixelSettings::class);
echo $settings->pixel_id; // e.g., "123456789012345"

// Via helper
echo pixel_settings()->pixel_id;
```

### Updating Settings

```php
$settings = app(PixelSettings::class);
$settings->pixel_id = '123456789012345';
$settings->save();

// Or via helper
$settings = pixel_settings();
$settings->pixel_id = '123456789012345';
$settings->save();
```

### Tracking Custom Events (Client-Side)

After including the Pixel script, you can track custom events in your Blade templates:

```blade
{{-- Standard events --}}
<script>
    fbq('track', 'Purchase', {value: 29.99, currency: 'USD'});
    fbq('track', 'Lead');
    fbq('track', 'CompleteRegistration');
</script>

{{-- Custom events --}}
<script>
    fbq('trackCustom', 'MyCustomEvent', {category: 'landing'});
</script>
```

## Configuration

This package uses **no config file**. All configuration is managed via `spatie/laravel-settings` in the database.

### Settings Migration

The migration creates a single setting in the `pixel` group:

```php
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pixel.pixel_id', null);
    }
};
```

### Adding New Settings

When adding a new setting property:

1. Add the property to `PixelSettings`:

```php
public bool $track_page_view = true;
```

2. Create a new settings migration:

```php
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pixel.track_page_view', true);
    }
};
```

3. Update `script.blade.php` if the setting affects the tracking script rendering.
4. Update the `pixel_settings()` helper if new accessor methods are needed.

## Testing Patterns

### Test Setup

The package uses Pest with `pestphp/pest-plugin-laravel` and `orchestra/testbench`.

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Run code formatting
composer format
```

### Writing Tests

```php
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

it('renders the facebook pixel when pixel_id is set', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '123456789012345';
    $settings->save();

    $view = $this->blade('@include("pixel::script")');

    $view->assertSee("fbq('init', '123456789012345')");
    $view->assertSee("fbq('track', 'PageView')");
    $view->assertSee('connect.facebook.net/en_US/fbevents.js');
});

it('does not render the script when pixel_id is null', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = null;
    $settings->save();

    $view = $this->blade('@include("pixel::script")');

    $view->assertDontSee('fbq');
    $view->assertDontSee('facebook');
});

it('renders the noscript fallback image', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '123456789012345';
    $settings->save();

    $view = $this->blade('@include("pixel::script")');

    $view->assertSee('https://www.facebook.com/tr?id=123456789012345');
    $view->assertSee('<noscript>', false);
});
```

### Testing the Helper Function

```php
it('pixel_settings() returns PixelSettings instance', function () {
    $settings = pixel_settings();

    expect($settings)->toBeInstanceOf(PixelSettings::class);
});

it('pixel_settings() reads the pixel_id', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '999888777666555';
    $settings->save();

    expect(pixel_settings()->pixel_id)->toBe('999888777666555');
});
```

### Testing Settings Independently

```php
it('has the correct default values', function () {
    $settings = app(PixelSettings::class);

    expect($settings->pixel_id)->toBeNull();
});

it('belongs to the pixel group', function () {
    expect(PixelSettings::group())->toBe('pixel');
});
```

### Testing the Facade

```php
use JeffersonGoncalves\Pixel\Facades\PixelSettings;

it('facade resolves to PixelSettings class', function () {
    expect(PixelSettings::getFacadeRoot())
        ->toBeInstanceOf(\JeffersonGoncalves\Pixel\Settings\PixelSettings::class);
});
```
