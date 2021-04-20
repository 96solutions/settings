<?php

namespace NinetySixSolutions\Settings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SettingsManager::class, function ($app) {
            return new SettingsManager($app);
        });

        $this->app->singleton(SettingsStorageInterface::class, function ($app) {
            return $app->make(SettingsManager::class)->driver();
        });

        // Facade
        $this->app->singleton('Settings', function ($app) {
            return new Settings($app->make(SettingsStorageInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'settings');

        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('settings.php'),
        ], 'settings');

        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'settings');
    }
}
