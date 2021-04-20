<?php
namespace NinetySixSolutions\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NinetySixSolutions\Settings\Settings
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Settings';
    }
}
