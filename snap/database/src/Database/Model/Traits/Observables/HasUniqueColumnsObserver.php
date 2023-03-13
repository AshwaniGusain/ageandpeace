<?php

namespace Snap\Database\Model\Traits\Observables;

use Illuminate\Database\Eloquent\Model;

class HasUniqueColumnsObserver {

    /**
     * Adds unique column validation
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function saving(Model $model)
    {
        foreach ($model->uniqueColumns() as $key => $column) {
            if (is_string($key)) {
                $uniqueColumn = $column;
                $column = $key;
            } else {
                $uniqueColumn = $model->getUniqueColumnExcludeValue();
            }

            $value = $model->{$uniqueColumn};

            if (in_array('Snap\Database\Model\Traits\HasValidation', class_uses_recursive(new static))){
                if (is_array($column)) {
                    foreach ($column as $col) {
                        static::addRule($col, 'required');
                        //static::addRule($col, 'unique:'.$model->getTable().','.$col.',{'.$uniqueColumn.'}');
                        static::addRule($col, 'unique:'.$model->getTable().','.$col.','.$value);
                    }
                } else {
                    static::addRule($column, 'required');
                    static::addRule($column, 'unique:'.$model->getTable().','.$column.','.$value);
                }
            }
        }
    }
}
