<?php

namespace Snap\Admin\Ui\Dashboard;

use Snap\Admin\Ui\BasePage;
use Snap\Ui\Components\Bootstrap\Grid;

class DashboardPage extends BasePage
{
    protected $view = 'admin::module.dashboard';

    protected $data = [
        ':grid'   => Grid::class,
        'widgets' => [],
    ];

    public function initialize()
    {
        $i = 0;
        foreach (config('snap.admin.dashboard', []) as $classes) {
            if (is_array($classes)) {
                $widgets = [];
                foreach ($classes as $class) {
                    $widgets[$i] = $this->createWidget($class, $i);
                    $i++;
                }
                $this->grid->add($widgets);
            } else {
                $this->grid->add($this->createWidget($classes), $i);
                $i++;
            }
        }

        $this->setPageTitle([$this->module->pluralName()]);
    }

    public function createWidget($class)
    {
        $widget = ui($class);
        $url = \Admin::modules()->get('dashboard')->url($widget->uri());
        $placeholder = new Placeholder(['url' => $url]);

        return $placeholder;
    }
}