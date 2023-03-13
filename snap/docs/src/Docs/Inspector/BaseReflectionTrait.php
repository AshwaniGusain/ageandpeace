<?php

namespace Snap\Docs\Inspector;

trait BaseReflectionTrait {

    public function comment()
    {
        return new CommentInspector($this->reflection->getDocComment());
    }

    public function hasComment()
    {
        return $this->reflection->getDocComment() ?? false;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->reflection, $method], $args);
    }
}