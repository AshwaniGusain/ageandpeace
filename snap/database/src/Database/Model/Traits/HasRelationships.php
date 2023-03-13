<?php

namespace Snap\Database\Model\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Snap\Database\Model\Relationships\SharedRelationship;

trait HasRelationships {

    /**
     * Instantiate a new BelongsToMany relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $table
     * @param  string  $foreignPivotKey
     * @param  string  $relatedPivotKey
     * @param  string  $parentKey
     * @param  string  $relatedKey
     * @param  string  $relationName
     * @return \Snap\Database\Model\Relationships\SharedRelationship
     */
    protected function newSharedRelationship(Builder $query, $parent, $table, $foreignPivotKey, $relatedPivotKey,
        $parentKey, $relatedKey, $relationName = null)
    {
        return new SharedRelationship($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }

    /**
     * Returns a belongsToMany relationship object using a shared table.
     * Handy if you don't like creating pivot tables for every belongsToMay relationship.
     *
     * @param $model
     * @param null $tableInfo
     * @return \Snap\Database\Model\Relationships\SharedRelationship
     */
    public function sharedRelationship($model, $context = null, $tableInfo = null)
    {
        $relation = $this->guessBelongsToManyRelation();
        if (is_string($model)) {
            $foreignModel = $model;
            $model = new $model;
        } else {
            $foreignModel = get_class($model);
        }

        $tableInfo = $this->getRelationshipTableInfo($tableInfo);

        // First, we'll need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we'll make the query
        // instances as well as the relationship instances we need for this.
        $instance = $this->newRelatedInstance($foreignModel);
        $sharedRelationship = $this->newSharedRelationship(
            $instance->newQuery(),
            $this,
            $tableInfo['table'],
            $tableInfo['key_column'],
            $tableInfo['foreign_key_column'],
            $this->getKeyName(),
            $instance->getKeyName(),
            $relation
        );

        $sharedRelationship
            ->wherePivot($tableInfo['table_column'], $this->getTable())
            ->wherePivot($tableInfo['foreign_table_column'], $model->getTable())
            ->withTimestamps()
        ;

        if ($context && !empty($tableInfo['context_column'])) {
            $sharedRelationship->where($tableInfo['context_column'], $context);
        }

        return $sharedRelationship;
    }

    /**
     * The reverse of sharedRelationship method above.
     *
     * @param $model
     * @param null $tableInfo
     * @return \Snap\Database\Model\Relationships\SharedRelationship
     */
    public function sharedForeignRelationship($model, $context = null, $tableInfo = null)
    {
        $tableInfo = $this->getRelationshipTableInfo($tableInfo);

        // Flip the key/table and foreign_key/foreign_table info to get the opposite relationship.
        $foreignTableInfo = [
            'key_column' => $tableInfo['foreign_key_column'],
            'table_column' => $tableInfo['foreign_table_column'],
            'foreign_key_column' => $tableInfo['key_column'],
            'foreign_table_column' => $tableInfo['table_column'],
        ];

        return $this->sharedRelationship($model, $context, $foreignTableInfo);
    }

    /**
     * Returns the default table info for a shared relationship.
     *
     * @param null $tableInfo
     * @return array|null
     */
    public function getRelationshipTableInfo($tableInfo = null)
    {
        $defaultTableInfo = [
            'table' => (defined('static::SHARED_RELATIONSHIPS_TABLE') ? static::SHARED_RELATIONSHIPS_TABLE : 'snap_relationships'),
            'key_column' => 'key',
            'table_column' => 'table',
            'foreign_key_column' => 'foreign_key',
            'foreign_table_column' => 'foreign_table',
            'context_column' => 'context',
        ];

        $tableInfo = (empty($tableInfo)) ? $defaultTableInfo : array_merge($defaultTableInfo, $tableInfo);

        return $tableInfo;
    }

    /**
     * Returns an array of all the different relationship types for the Model.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getRelationshipTypes()
    {
        $relationships = [];
        $mirror = new \ReflectionClass($this);

        $methods = $mirror->getMethods();

        foreach ($methods as $method) {

            if ($method->getReturnType()) {

                $returnType = class_basename($method->getReturnType()->getName());
                foreach (['HasOne', 'BelongsTo', 'HasMany', 'BelongsToMany', 'HasManyThrough', 'MorphTo', 'MorphMany', 'MorphToMany', 'MorphedByMany', 'MetaFields', 'SharedRelationship'] as $t) {
                    if ($returnType == $t) {
                        $relationships[$t][] = $method->getName();
                    }
                }
            }
        }

        return $relationships;
    }

    /**
     * Loads all relationships.
     *
     * @returns $this
     * @throws \ReflectionException
     */
    public function loadAll() {
        $relationships = $this->getRelationshipTypes();
        foreach ($relationships as $type) {
            foreach ($type as $method) {
                $this->load($method);
            }
        }

        return $this;
    }

}