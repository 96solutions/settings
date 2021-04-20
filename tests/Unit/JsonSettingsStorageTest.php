<?php

namespace Tests\Unit;



class JsonSettingsStorageTest extends AbstractSettingsStorageTest
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('settings.driver', 'json');
    }
}
