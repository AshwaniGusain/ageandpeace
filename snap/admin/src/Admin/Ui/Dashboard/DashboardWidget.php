<?php

namespace Snap\Admin\Ui\Dashboard;

use Snap\Admin\Ui\Dashboard\Contracts\DashboardWidgetInterface;
use Snap\Ui\UiComponent;

abstract class DashboardWidget extends UiComponent implements DashboardWidgetInterface
{
    public static function uri()
    {
        if (isset(static::$name)) {
            return static::$name;
        }

        return strtolower(class_basename(new static));
    }
}