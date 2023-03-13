<?php

namespace Snap\Ui\DataTypes;

abstract class BaseDataType implements UiDataTypeInterface {

    abstract function cast($value, $ui);

    protected function parseClassParameters($value, $ui)
    {
        $value = '\\'.trim($value,'\\');

        if (preg_match('/(.*?)\[(.*)\]/', $value, $match)){
            $class = $match[1];
            $valueParts = explode(',', $match[2]);
        } else {
            $class = $value;
        }

        $params = null;

        if ( ! empty($valueParts)) {
            foreach ($valueParts as $param) {
                $param = trim($param);
                $params[$param] = $ui->$param;
            }
        }

        return [$class, $params];
    }

}