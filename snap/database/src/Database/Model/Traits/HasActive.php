<?php

namespace Snap\Database\Model\Traits;

use Snap\Database\Model\Traits\Scopes\ActiveScope;

trait HasActive {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootHasActive()
    {
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getActiveColumn()
    {
        return defined('static::ACTIVE_COLUMN') ? static::ACTIVE_COLUMN : 'active';
    }

    /**
     * Get the published value for applying the scope.
     *
     * @return mixed
     */
    public function getActiveValue()
    {
        return defined('static::ACTIVE_VALUE') ? static::ACTIVE_VALUE : 1;
    }

    /**
     * Get the inactive value for applying the scope.
     *
     * @return mixed
     */
    public function getInActiveValue()
    {
        return defined('static::INACTIVE_VALUE') ? static::INACTIVE_VALUE : 0;
    }

    /**
     * Get the fully qualified column name for applying the scope.
     *
     * @return string
     */
    public function getQualifiedActiveColumn()
    {
        $column = $this->getActiveColumn();
        if (strpos($column, '.') === false) {
            return $this->getTable().'.'.$this->getActiveColumn();
        }

        return $column;
    }

    /**
     * Determine if a model is active.
     *
     * @return boolean
     */
    public function isActive()
    {
        return (boolean) $this->attributes[$this->getActiveColumn()] == $this->getActiveValue();
    }

    /**
     * Alias to isActive but behaves like a property (e.g. is_active).
     *
     * @return boolean
     */
    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }

    public function getActiveFriendlyAttribute()
    {
        return $this->isActive() ? trans('db::traits.yes') : trans('db::traits.no');
    }

    public function useActiveScope()
    {
        return isset(static::$useActive) ? (bool) static::$useActive : true;
    }

    public function setActiveScope($activeScope)
    {
        static::$useActive = $activeScope;

        return $this;
    }

}
