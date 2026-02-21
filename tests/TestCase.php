<?php

namespace JeffersonGoncalves\Pixel\Tests;

use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Pixel\PixelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelSettings\LaravelSettingsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelSettingsServiceProvider::class,
            PixelServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase(): void
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function ($table) {
                $table->id();
                $table->string('group');
                $table->string('name');
                $table->boolean('locked')->default(false);
                $table->json('payload');
                $table->timestamps();

                $table->unique(['group', 'name']);
            });
        }

        $this->seedDefaultSettings();
    }

    protected function seedDefaultSettings(): void
    {
        \DB::table('settings')->insert([
            'group' => 'pixel',
            'name' => 'pixel_id',
            'locked' => false,
            'payload' => json_encode(null),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
