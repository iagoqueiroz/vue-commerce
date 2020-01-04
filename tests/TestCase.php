<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static $db_filled = false;

    public static function fillTestDatabase()
    {
        echo "\n---- Initialized Database ----\n";
        Storage::deleteDirectory('public/uploads');
        Artisan::call('migrate:fresh --seed');
    }

    public function setUp(): void
    {
        parent::setUp();

        if(!static::$db_filled) {
            static::$db_filled = true;
            static::fillTestDatabase();
        }
    }
}