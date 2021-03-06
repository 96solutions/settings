<?php
namespace NinetySixSolutions\Settings;

use NinetySixSolutions\Settings\Models\Settings as SettingsModel;

/**
 * Class EloquentSettingsStorage
 *
 * @package NinetySixSolutions\Settings
 */
class EloquentSettingsStorage extends SettingsModel implements SettingsStorageInterface
{
    /**
     * Create new storage instance
     *
     * @param string|null $connection
     * @param string      $table
     * @param array       $attributes
     */
    public function __construct($connection = null, $table = 'settings', array $attributes = [])
    {
        parent::__construct($attributes);
        $connection = $connection !== null ? $connection : config('settings.connection');
        $table = !empty($table) ? $table : config('settings.table');

        $this->setConnection($connection);
        $this->setTable($table);
    }

    /**
     * Create a new instance of the given storage
     *
     * @param array      $attributes
     * @param bool|false $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false): EloquentSettingsStorage
    {
        $model = new static($this->connection, $this->table, $attributes);
        $model->exists = $exists;

        return $model;
    }

    /**
     * Execute a query for a single record by existed 'where' rules
     *
     * @return mixed
     */
    public function firstOrFail()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * Add a basic where clause to the query
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function where($key, $value)
    {
        return static::__call(__FUNCTION__, func_get_args());
    }
}
