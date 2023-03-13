<?php

namespace Snap\Admin\Http\Controllers;

class ModuleController extends AdminBaseController
{
    protected $module = null;

    public function initialize()
    {
//        $this->module = $this->getModule();
        $this->module = $this->admin->modules()->current();

        if ($this->module) {
            $this->module->boot();
//            $this->admin->modules()->setCurrent($this->module);
            $this->app->instance('ActiveSnapModule', $this->module);
        }
    }

}
