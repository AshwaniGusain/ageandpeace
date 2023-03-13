<?php

namespace Snap\Form\Contracts;

interface NestableInterface
{
    public function setNested($nested);
    public function isNested();
}