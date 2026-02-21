<?php

use JeffersonGoncalves\Pixel\Settings\PixelSettings;

if (! function_exists('pixel_settings')) {
    function pixel_settings(): PixelSettings
    {
        return app(PixelSettings::class);
    }
}
