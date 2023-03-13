<?php

namespace Snap\Database\Model\Traits;

use Snap\Database\Model\Traits\Observables\HasUserInfoObserver;

trait HasUserInfo {

    /**
     * Boot the trait. Adds an observer class for adding a users info.
     *
     * @return void
     */
    public static function bootHasUserInfo()
    {
        static::observe(HasUserInfoObserver::class);
    }

    /**
     * Returns the belongsTo user object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        $relatedModel = $this->getCreatedByColumnModel();
        $foreignKey = $this->getCreatedByColumn();
        $localKey = $this->getCreatedByParentColumn();

        if (class_exists($relatedModel)) {
            return $this->belongsTo($relatedModel, $foreignKey, $localKey);
        }
    }

    /**
     * Returns the the created_by column related model for the relationship.
     *
     * @return string
     */
    public function getCreatedByColumnModel()
    {
        return defined('static::CREATED_BY_MODEL') ? static::CREATED_BY_MODEL : '\App\Models\User';
    }

    /**
     * Returns the the created_by foreign key for the relationship.
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY_COLUMN') ? static::CREATED_BY_COLUMN : 'created_by_id';
    }

    /**
     * Returns the the created_by column for the relationship.
     *
     * @return string
     */
    public function getCreatedByParentColumn()
    {
        return defined('static::CREATED_BY_PARENT_COLUMN') ? static::CREATED_BY_COLUMN : 'id';
    }

    /**
     * Returns the belongsTo user object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastUpdatedBy()
    {
        $relatedModel = $this->getLastUpdatedByColumnModel();
        $foreignKey = $this->getLastUpdatedByColumn();
        $localKey = $this->getLastUpdatedByParentColumn();

        if (class_exists($relatedModel)) {
            return $this->belongsTo($relatedModel, $foreignKey, $localKey);
        }
    }

    /**
     * Returns the the updated_by column related model for the relationship.
     *
     * @return string
     */
    public function getLastUpdatedByColumnModel()
    {
        return defined('static::LAST_UPDATED_BY_MODEL') ? static::LAST_UPDATED_BY_MODEL : '\App\Models\User';
    }

    /**
     * Returns the the updated_by foreign key for the relationship.
     *
     * @return string
     */
    public function getLastUpdatedByColumn()
    {
        return defined('static::LAST_UPDATED_BY_COLUMN') ? static::LAST_UPDATED_BY_COLUMN : 'updated_by_id';
    }

    /**
     * Returns the the updated_by column for the relationship.
     *
     * @return string
     */
    public function getLastUpdatedByParentColumn()
    {
        return defined('static::LAST_UPDATED_BY_PARENT_COLUMN') ? static::LAST_UPDATED_BY_PARENT_COLUMN : 'id';
    }

}