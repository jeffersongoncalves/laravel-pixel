<?php

namespace JeffersonGoncalves\Pixel\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\Pixel\Settings\PixelSettings as PixelSettingsClass;

/**
 * @see \JeffersonGoncalves\Pixel\Settings\PixelSettings
 */
class PixelSettings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PixelSettingsClass::class;
    }
}
