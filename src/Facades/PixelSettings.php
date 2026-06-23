<?php

namespace JeffersonGoncalves\Pixel\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\Pixel\Settings\PixelSettings as PixelSettingsClass;

/**
 * @property ?string $pixel_id
 *
 * @see PixelSettingsClass
 */
class PixelSettings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PixelSettingsClass::class;
    }
}
