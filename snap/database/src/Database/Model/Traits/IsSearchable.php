<?php

namespace Snap\Database\Model\Traits;

use Snap\Database\DBUtil;

trait IsSearchable {

    public function scopeSearch($query, $term, $fields = [])
    {
        if ( ! $term) return $query;

        $model = $query->getModel();

        if (property_exists($this, 'searchFields')) {
            $fields = $this->searchFields;
        }

        if (empty($fields) || $fields == '*') {
            $fields = DBUtil::columnList($model->getTable());

        } elseif (is_string($fields)) {
            $fields = [$fields];
        }

        $query->where(function($query) use ($fields, $term, $model){
            foreach ($fields as $field) {
                if (strpos($field, ':') === false) {
                    $field = $model->getTable() . '.' . $field;
                    $query->orWhere($field, 'like', '%'.$term.'%');
                } else {
                    $divider = strpos($field, ':');
                    $query->orWhereHas(substr($field, 0, $divider), function($query) use($field, $divider, $term) {
                        $query->where(substr($field, $divider + 1, strlen($field)), 'like', '%'. $term . '%');
                    });
                }
            }
        });

        return $query;
    }
}
