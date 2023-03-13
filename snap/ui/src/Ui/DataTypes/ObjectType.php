<?php

namespace Snap\Ui\DataTypes;

class ObjectType extends BaseDataType {

    public function cast($value, $ui)
    {
        list($class, $params) = $this->parseClassParameters($value, $ui);

        if ( ! empty($params)) {
            $mirror = new \ReflectionClass($class);
            $parameters = $mirror->getMethod('__construct')->getParameters();
            $p = [];
            foreach ($parameters as $param) {
                $p[] = $param->getName();
            }
            $params = array_combine($p, $params);
            //return app()->make($class, [$params]);
//            $params['data']['parant'] = $this;
//            return new $class($params);
        }

        if (is_callable($class)) {
            return $class($params);
        } else {
            if (!empty($params)) {
                return app()->make($class, [$params]);
            }
            return app()->make($class);
        }
    }
}