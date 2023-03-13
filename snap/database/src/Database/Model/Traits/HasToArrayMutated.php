<?php

namespace Snap\Database\Model\Traits;

trait HasToArrayMutated {

    /**
     * This method overrides the toArray to get all the mutated attributes.
     * 
     * @return array
     */ 
    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->getMutatedAttributes() as $key) {
            if ( ! array_key_exists($key, $array)) {
                $array[$key] = $this->{$key};   
            }
        }

        return $array;
    }
}
