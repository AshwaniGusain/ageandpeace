<?php

namespace Snap\Ui\DataTypes;

class ConfigType implements UiDataTypeInterface {

    public function cast($value, $ui)
    {
        return config($value);
    }
}