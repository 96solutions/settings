<?php
namespace NinetySixSolutions\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Settings
 *
 * @package NinetySixSolutions\Settings\Facades
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'Settings';
    }
}
