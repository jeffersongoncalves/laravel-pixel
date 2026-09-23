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
        $app['config']->set('database.connections.testing', $this->testing_connection());
    }

    protected function setUpDatabase(): void
    {
        // Real databases (MySQL/PostgreSQL in CI) keep tables and rows between tests.
        Schema::dropAllTables();

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

    /**
     * The original in-memory SQLite connection by default; CI (tests.yml) sets
     * PIXEL_TEST_DB_* to run the same suite on MySQL and PostgreSQL. Not DB_CONNECTION:
     * Testbench pins it to "testing", which would always win over a driver read from it.
     *
     * @return array<string, mixed>
     */
    protected function testing_connection(): array
    {
        $driver = env('PIXEL_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'sqlite') {
            return [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ];
        }

        return [
            'driver' => $driver,
            'host' => env('PIXEL_TEST_DB_HOST', '127.0.0.1'),
            'port' => env('PIXEL_TEST_DB_PORT'),
            'database' => env('PIXEL_TEST_DB_DATABASE', 'testing'),
            'username' => env('PIXEL_TEST_DB_USERNAME', 'root'),
            'password' => env('PIXEL_TEST_DB_PASSWORD', ''),
            'charset' => $driver === 'pgsql' ? 'utf8' : 'utf8mb4',
            'prefix' => '',
        ];
    }
}
