<?php

use Illuminate\Support\Facades\Vite;
use JeffersonGoncalves\Pixel\Settings\PixelSettings;

it('stamps the CSP nonce on every script tag', function () {
    Vite::useCspNonce('test-nonce');
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '123456789';
    $settings->save();

    app()->forgetInstance(PixelSettings::class);
    $html = (string) view('pixel::script')->render();

    preg_match_all('/<script\b[^>]*>/', $html, $tags);

    expect($tags[0])->not->toBeEmpty()->each->toContain('nonce="test-nonce"');
});

it('renders no nonce attribute when the app uses none', function () {
    $settings = app(PixelSettings::class);
    $settings->pixel_id = '123456789';
    $settings->save();

    app()->forgetInstance(PixelSettings::class);
    $html = (string) view('pixel::script')->render();

    expect($html)->toContain('<script')->not->toContain('nonce=');
});
