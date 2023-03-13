<?php

namespace Snap\Ui\DataTypes;

interface UiDataTypeInterface
{
    public function cast($value, $ui);
}
