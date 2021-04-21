<?php
namespace NinetySixSolutions\Settings;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Query\Builder;
use NinetySixSolutions\Settings\Exceptions\SettingNotFoundException;

/**
 * Class DbSettingsStorage
 *
 * @package NinetySixSolutions\Settings
 */
class DbSettingsStorage implements SettingsStorageInterface
{
    /**
     * @var ?string Db Connection Name
     */
    protected ?string $connection;

    /**
     * @var string Db Table Name
     */
    protected string $table;

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * @var ConnectionResolverInterface Database Manager
     */
    protected ConnectionResolverInterface $db;

    /**
     * @var ?string
     */
    public ?string $name;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var ?bool
     */
    public ?bool $active;

    /**
     * @var ?string
     */
    public ?string $module;

    /**
     * Create new storage instance
     *
     * @param ConnectionResolverInterface $db
     * @param string|null                 $connection
     * @param string                      $table
     */
    function __construct(ConnectionResolverInterface $db, ?string $connection = null, string $table = 'settings')
    {
        $this->connection = $connection !== null ? $connection : config('settings.connection');
        $this->table = !empty($table) ? $table : config('settings.table');
        $this->db = $db;
        $this->query = $this->newInstance();
    }

    /**
     * Create new connection instance to work with
     *
     * @return Builder
     */
    protected function newInstance(): Builder
    {
        $this->query = $this->db->connection($this->connection)->table($this->table);

        return $this->query;
    }

    /**
     * Add a basic where clause to the query
     *
     * @param $key
     * @param $value
     *
     * @return DbSettingsStorage
     */
    public function where($key, $value): DbSettingsStorage
    {
        $this->query->where($key, $value);

        return $this;
    }

    /**
     * Execute a query for a single record by existed 'where' rules
     *
     * @throws SettingNotFoundException
     *
     * @return DbSettingsStorage
     */
    public function firstOrFail(): DbSettingsStorage
    {
        $item = $this->query->first();
        $this->query = $this->newInstance();

        if (empty($item)) {
            throw new SettingNotFoundException();
        }

        $this->module = $item->module;
        $this->name = $item->name;
        $this->value = unserialize($item->value);
        $this->active = (bool) $item->active;

        return $this;
    }

    /**
     * Save setting record (update or create) to database
     *
     * @return bool
     */
    public function save(): bool
    {
        $query = $this->newInstance();

        return $query->updateOrInsert([
            'name'       => $this->name,
            'module'     => $this->module,
        ], [
            'name'       => $this->name,
            'value'      => serialize($this->value),
            'active'     => (bool) $this->active,
            'module'     => $this->module,
        ]);
    }

    /**
     * Delete a record from the database
     *
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $query = $this->newInstance();
            $affectedRows = $query->where('module', $this->module)->where('name', $this->name)->delete();

            $this->query = $this->newInstance();
            $this->name = null;
            $this->value = null;
            $this->active = null;
            $this->module = null;

            return (bool) ($affectedRows > 0);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear query params on instance clone
     */
    public function __clone()
    {
        $this->newInstance();
    }
}
