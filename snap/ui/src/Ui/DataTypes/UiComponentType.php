<?php

namespace Snap\Ui\DataTypes;

class UiComponentType extends BaseDataType {

    public function cast($value, $ui)
    {
        list($class, $data) = $this->parseClassParameters($value, $ui);

        $params['data'] = $data;
        $params['parent'] = $ui;

        return app()->make($class, $params);
    }
}