<?php

namespace Snap\Ui\DataTypes;

class ViewType implements UiDataTypeInterface {

    public function cast($value, $ui)
    {
        $value = '\\'.trim($value,'\\');

        if (preg_match('/(.*?)\[(.*)\]/', $value, $match)){
            $view = $match[1];
            $valueParts = explode(',', $match[2]);
        } else {
            $view = $value;
        }
        
        $data = [];

        if ( ! empty($valueParts)) {
            foreach ($valueParts as $param) {
                $data = trim($param);
                $data[$param] = $ui->$param;
            }
        }

        return view($view, $data);
    }
}

    