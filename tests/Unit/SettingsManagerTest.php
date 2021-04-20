<?php

namespace Tests\Unit;



use NinetySixSolutions\Settings\DbSettingsStorage;
use NinetySixSolutions\Settings\EloquentSettingsStorage;
use NinetySixSolutions\Settings\JsonSettingsStorage;
use NinetySixSolutions\Settings\SettingsManager;
use NinetySixSolutions\Settings\SettingsStorageInterface;
use Tests\TestCase;

class SettingsManagerTest extends TestCase
{
    public function testEloquentDriver()
    {
        $this->app['config']->set('settings.driver', 'eloquent');
        $settingsManager = $this->app->make(SettingsManager::class);
        $this->assertInstanceOf(SettingsManager::class, $settingsManager);
        $this->assertInstanceOf(EloquentSettingsStorage::class, $this->app->make(SettingsStorageInterface::class));
    }

    public function testDBDriver()
    {
        $this->app['config']->set('settings.driver', 'db');
        $this->app->make(SettingsManager::class);
        $this->assertInstanceOf(DbSettingsStorage::class, $this->app->make(SettingsStorageInterface::class));
    }

    public function testJsonDriver()
    {
        $this->app['config']->set('settings.driver', 'json');
        $this->app->make(SettingsManager::class);
        $this->assertInstanceOf(JsonSettingsStorage::class, $this->app->make(SettingsStorageInterface::class));
    }
}
