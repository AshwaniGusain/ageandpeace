<?php

namespace Snap\Database\Model\Relationships;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SharedRelationship extends BelongsToMany
{
    /**
     * Attach a model to the parent.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     * @param  bool   $touch
     * @return void
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        $defaultAttributes = [
            'table' => $this->parent->getTable(),
            'foreign_table' => $this->related->getTable(),
            'context' => ''
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        if (is_array($id)) {
            foreach ($id as $key => $val) {
                if (is_string($val)) {
                    $id[$key] = ['context' => $val];
                }
            }
        }

        parent::attach($id, $attributes, $touch);
    }
}