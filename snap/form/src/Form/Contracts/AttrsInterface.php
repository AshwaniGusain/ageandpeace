<?php

namespace Snap\Form\Contracts;

interface AttrsInterface
{
    public function setAttrs($attrs);
    public function &getAttrs();
    public function appendAttr($key, $value);
    public function getAttrsStr();
    public function setAttr($key, $value);
    public function getAttr($key);
    public function removeAttr($key);
}