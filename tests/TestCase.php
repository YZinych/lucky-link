<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! app()->environment('testing')) {
            echo "ABORT: Not in testing environment!\n";
            exit(1);
        }

        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }
}
