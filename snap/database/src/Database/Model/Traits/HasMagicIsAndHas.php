<?php

namespace Snap\Database\Model\Traits;

trait HasMagicIsAndHas {

    /**
     * Add some additional magic for accessing model attributes with prefixes of "is_" and "has_".
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $val = $this->getAttribute($key);

        if (is_null($val)) {
            $attributes = $this->getAttributes();
            if (preg_match("/^is_(.*)/", $key, $found)) {
                if (array_key_exists($found[1], $attributes)) {

                    $isTrue = is_true($attributes[$found[1]]);

                    // Check boolean property first before querying the DB again
                    if (isset(static::$booleans) && in_array($found[1], static::$booleans)) {
                        return $isTrue;
                    }
                }

            } else if (preg_match("/^has_(.*)/", $key, $found))	{
                if (array_key_exists($found[1], $attributes)) {
                    return !empty($attributes[$found[1]]);
                }
            }
        }

        return $val;
    }

}