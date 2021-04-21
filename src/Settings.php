<?php

namespace NinetySixSolutions\Settings;

use \Exception;
use NinetySixSolutions\Settings\Exceptions\SettingNotFoundException;

/**
 *   Settings Management Class
 */
class Settings
{
    /**
     * Fully-qualified class name of an $instance variable
     *
     * @var SettingsStorageInterface
     */
    protected SettingsStorageInterface $storage;

    /**
     * Init settings storage
     *
     * @param SettingsStorageInterface $storage
     */
    function __construct(SettingsStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Save / Update settings record
     *
     * @param string  $name   setting name
     * @param mixed   $value  value
     * @param string  $module module name if NULL global settings
     * @param boolean $active if setting is active
     *
     * @return SettingsStorageInterface
     *
     * @throws Exception
     */
    public function set(string $name, $value, string $module = 'global', bool $active = true): SettingsStorageInterface
    {
        if ($this->has($name, $module)) {
            return $this->update($name, $value, $module, $active);
        }

        $this->create()->save($name, $value, $module, $active);

        return $this->storage;
    }

    /**
     * Check if setting exists
     *
     * @param string $name   setting name
     * @param string $module module name if NULL global settings
     *
     * @return boolean
     */
    public function has(string $name, string $module = 'global'): bool
    {
        try {
            $this->getInstance($name, $module);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting setting value
     *
     * @param string $name    setting name
     * @param string $module  module name if NULL global settings
     * @param mixed  $default default value if setting don't exists
     *
     * @return mixed value, default or NULL
     */
    public function get(string $name, string $module = 'global', $default = null)
    {
        if ($this->has($name, $module)) {
            return $this->storage->value;
        } elseif (!is_null($default)) {
            return $default;
        }

        return null;
    }

    /**
     * Update an existing record
     *
     * @param string  $name   setting name
     * @param mixed   $value  value
     * @param string  $module module name if NULL global settings
     * @param boolean $active if setting is active
     *
     * @return SettingsStorageInterface
     *
     * @throws Exception
     */
    public function update(string $name, $value, string $module = 'global', bool $active = true)
    {
        $this->getInstance($name, $module);
        $this->save($name, $value, $module, $active);

        return $this->storage;
    }

    /**
     * Check if setting is active
     *
     * @param string $name   setting name
     * @param string $module module name if NULL global settings
     *
     * @return boolean
     *
     * @throws Exception
     */
    public function isActive(string $name, string $module = 'global'): bool
    {
        $this->getInstance($name, $module);

        return boolval($this->storage->active);
    }

    /**
     * Make setting active
     *
     * @param string $name   active
     * @param string $module module name if NULL global settings
     *
     * @return boolean
     *
     * @throws Exception
     */
    public function activate(string $name, string $module = 'global'): bool
    {
        $this->getInstance($name, $module);
        $this->storage->active = true;
        $this->storage->save();

        return true;
    }

    /**
     * Make setting inactive
     *
     * @param string $name   setting name
     * @param string $module module name if NULL global settings
     *
     * @return boolean
     *
     * @throws Exception
     */
    public function deactivate(string $name, string $module = 'global'): bool
    {
        $this->getInstance($name, $module);
        $this->storage->active = false;
        $this->storage->save();

        return true;
    }

    /**
     * Delete setting record
     *
     * @param string $name   setting name
     * @param string $module module name if NULL global settings
     *
     * @return boolean
     *
     * @throws Exception
     */
    public function delete(string $name, string $module = 'global'): bool
    {
        $this->getInstance($name, $module);
        $this->storage->delete();

        return true;
    }

    /**
     * Save data in db
     *
     * @param string  $name   name
     * @param mixed   $value  value
     * @param string  $module module name if NULL global settings
     * @param boolean $active if setting is active
     *
     * @return void
     */
    private function save(string $name, $value, string $module = 'global', bool $active = true): void
    {
        $this->storage->name = $name;
        $this->storage->value = $value;
        $this->storage->active = $active;
        $this->storage->module = $module;

        $this->storage->save();
    }

    /**
     * Looking for settings by name and module
     *
     * @param string $name   settings name
     * @param string $module module name if NULL global settings
     *
     * @return void
     *
     * @throws Exception
     */
    private function getInstance(string $name, string $module = 'global'): void
    {
        try {
            $instance = clone $this->storage;
            $this->storage = $instance->where('name', $name)->where('module', $module)->firstOrFail();
        } catch (Exception $e) {
            $this->throwException($name, $module);
        }
    }

    /**
     * Create new setting model instance
     *
     * @return Settings
     */
    private function create(): Settings
    {
        $this->storage = clone $this->storage;

        return $this;
    }

    /**
     * throw Exception
     *
     * @param string $name   setting name
     * @param string $module module name if NULL global settings
     *
     * @throws Exception
     */
    private function throwException(string $name, string $module = 'global')
    {
        throw new SettingNotFoundException(sprintf('Setting %s not found in module %s', $name, $module));
    }
}
