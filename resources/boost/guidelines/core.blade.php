## Laravel Pixel

### Overview

Laravel package that integrates Meta (Facebook) Pixel into Blade templates using `spatie/laravel-settings` for database-stored configuration. Renders the Facebook Pixel JavaScript SDK and a noscript fallback for tracking page views and conversions.

**Namespace:** `JeffersonGoncalves\Pixel`
**Service Provider:** `PixelServiceProvider` (extends `Spatie\LaravelPackageTools\PackageServiceProvider`)

### Key Concepts

- **Settings-driven**: All configuration lives in `PixelSettings` (group: `pixel`), not in config files.
- **Blade view**: Include `pixel::script` in your layout to render the Facebook Pixel code.
- **Facade + Helper**: Access settings via `PixelSettings` facade or `pixel_settings()` helper.
- **Auto-discovery**: Service provider and facade alias are auto-discovered via `composer.json`.
- **Minimal**: Single setting (`pixel_id`) -- no complex configuration needed.

### Settings (spatie/laravel-settings)

Settings class: `JeffersonGoncalves\Pixel\Settings\PixelSettings`
Group: `pixel`

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `pixel_id` | `?string` | `null` | Meta (Facebook) Pixel ID |

@verbatim
<code-snippet name="read-settings" lang="php">
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

// Via container
$settings = app(PixelSettings::class);
$settings->pixel_id; // ?string

// Via helper function
$settings = pixel_settings();
$settings->pixel_id;

// Via facade
use JeffersonGoncalves\Pixel\Facades\PixelSettings;
$pixelId = app(PixelSettings::getFacadeAccessor())->pixel_id;
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="update-settings" lang="php">
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

$settings = app(PixelSettings::class);
$settings->pixel_id = '123456789012345';
$settings->save();
</code-snippet>
@endverbatim

### Configuration

No config file is published. All configuration is managed through the `PixelSettings` class.

**Publish settings migration:**

@verbatim
<code-snippet name="publish-migration" lang="bash">
php artisan vendor:publish --tag="pixel-settings-migrations"
php artisan migrate
</code-snippet>
@endverbatim

### Blade Integration

Include the tracking script in your layout's `<head>`:

@verbatim
<code-snippet name="blade-include" lang="blade">
<head>
    @include('pixel::script')
</head>
</code-snippet>
@endverbatim

The script renders:

@verbatim
<code-snippet name="rendered-output" lang="html">
<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s){...}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '123456789012345');
    fbq('track', 'PageView');
</script>
<noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=123456789012345&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
</code-snippet>
@endverbatim

### Conventions

- Settings group name: `pixel`
- View namespace: `pixel`
- Package name: `laravel-pixel`
- Migration publish tag: `pixel-settings-migrations`
- Helper function: `pixel_settings()` returns `PixelSettings` instance
- Facade: `JeffersonGoncalves\Pixel\Facades\PixelSettings` (alias: `PixelSettings`)
- The script only renders when `pixel_id` is not null/empty.
- Includes both JS snippet and `<noscript>` fallback image for tracking.
- No models or relationships -- this is a script-injection package.
- PHP 8.2+ required, Laravel 11+, spatie/laravel-settings ^3.3.
