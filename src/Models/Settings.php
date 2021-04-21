<?php

namespace NinetySixSolutions\Settings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Settings
 *
 * @package NinetySixSolutions\Settings\Models
 */
class Settings extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Set setting value as json if array.
     *
     * @param mixed $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = serialize($value);
    }

    /**
     * Return setting value as json if array.
     *
     * @param  mixed $value
     *
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return unserialize($value);
    }
}
