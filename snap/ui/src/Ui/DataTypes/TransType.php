<?php

namespace Snap\Ui\DataTypes;

class TransType implements UiDataTypeInterface {

    public function cast($value, $ui)
    {
        return trans($value);
    }
}