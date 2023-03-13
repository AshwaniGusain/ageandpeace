<?php

namespace Snap\Docs\Inspector;

use Snap\Support\Contracts\ToString;

class MethodInspector implements ToString
{
    use BaseReflectionTrait;

    public $name;
    protected $reflection;
    protected $params;

    public function __construct(\ReflectionMethod $method)
    {
        $this->reflection = $method;
        $this->name = $this->reflection->getName();
    }

    public function params()
    {
        if (!isset($this->params)) {
            $params = [];

            foreach ($this->reflection->getParameters() as $param) {
                $params[$param->getName()] = new ParameterInspector($param);
            }

            $this->params = new ParametersCollection($params);
        }

        return $this->params;
    }

    public function isInherited()
    {
        $class = $this->reflection;
        //while($class->getDeclaringClass() ) {
        //
        //}
    }

    public function asString()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->asString();
    }

}