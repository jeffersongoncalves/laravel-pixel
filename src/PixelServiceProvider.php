<?php

namespace JeffersonGoncalves\Pixel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PixelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-pixel')
            ->hasConfigFile('pixel')
            ->hasViews();
    }
}
