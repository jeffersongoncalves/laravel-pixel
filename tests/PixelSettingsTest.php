<?php

use JeffersonGoncalves\Pixel\Facades\PixelSettings as PixelSettingsFacade;
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

it('resolves PixelSettings from the container', function () {
    $settings = app(PixelSettings::class);

    expect($settings)->toBeInstanceOf(PixelSettings::class);
});

it('has null as default pixel_id', function () {
    $settings = app(PixelSettings::class);

    expect($settings->pixel_id)->toBeNull();
});

it('persists pixel_id changes', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '123456789';
    $settings->save();

    app()->forgetInstance(PixelSettings::class);
    $fresh = app(PixelSettings::class);

    expect($fresh->pixel_id)->toBe('123456789');
});

it('returns PixelSettings via helper function', function () {
    expect(pixel_settings())->toBeInstanceOf(PixelSettings::class);
});

it('returns PixelSettings via Facade', function () {
    expect(PixelSettingsFacade::getFacadeRoot())->toBeInstanceOf(PixelSettings::class);
});

it('has correct group name', function () {
    expect(PixelSettings::group())->toBe('pixel');
});

it('does not render script when pixel_id is null', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = null;
    $settings->save();

    $html = view('pixel::script')->render();

    expect($html)->not->toContain('fbevents.js');
});

it('does not render script when pixel_id is empty string', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '';
    $settings->save();

    $html = view('pixel::script')->render();

    expect($html)->not->toContain('fbevents.js');
});

it('renders script when pixel_id is set', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '123456789';
    $settings->save();

    app()->forgetInstance(PixelSettings::class);

    $html = view('pixel::script')->render();

    expect($html)
        ->toContain('fbevents.js')
        ->toContain("fbq('init', '123456789')");
});

it('renders noscript fallback with pixel_id', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '987654321';
    $settings->save();

    app()->forgetInstance(PixelSettings::class);

    $html = view('pixel::script')->render();

    expect($html)->toContain('https://www.facebook.com/tr?id=987654321');
});

it('registers PixelSettings in settings config', function () {
    $settings = config('settings.settings');

    expect($settings)->toContain(PixelSettings::class);
});
