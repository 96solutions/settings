<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function getPackageAliases($app)
    {
        return [
            'Settings' => \NinetySixSolutions\Settings\Facades\Settings::class,
        ];
    }

    protected function getPackageProviders($app)
    {
        return [
            \NinetySixSolutions\Settings\SettingsServiceProvider::class,
        ];
    }
}
