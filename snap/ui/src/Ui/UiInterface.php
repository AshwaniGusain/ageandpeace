<?php

namespace Snap\Ui;

interface UiInterface
{
    public function with($key, $value = null);
    public function getData();
    public function root();
    public function parent();
    public function visible(bool $visible);
}
