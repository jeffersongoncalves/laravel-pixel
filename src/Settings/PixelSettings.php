<?php

namespace JeffersonGoncalves\Pixel\Settings;

use Spatie\LaravelSettings\Settings;

class PixelSettings extends Settings
{
    public ?string $pixel_id = null;

    public static function group(): string
    {
        return 'pixel';
    }
}
