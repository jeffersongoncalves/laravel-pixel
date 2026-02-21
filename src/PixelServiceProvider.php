<?php

namespace JeffersonGoncalves\Pixel;

use Illuminate\Support\Facades\Config;
use JeffersonGoncalves\Pixel\Settings\PixelSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
