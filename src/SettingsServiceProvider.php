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
        //
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
