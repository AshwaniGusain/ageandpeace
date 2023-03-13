<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;

/*
$ajax = AjaxService::make();
$ajax->whitelist(['options'])
;
*/
class AjaxService
{
    public $whitelist = [];

    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function getModuleMethod($name)
    {
        $method = 'ajax'.studly_case($name);
        if ($this->isWhitelisted($name) && method_exists($this->module, $method)) {
            return $method;
        }

        return null;
    }

    public function whitelist($methods)
    {
        $methods = is_array($methods) ? $methods : func_get_args();

        $this->whitelist = $methods;

        return $this;
    }

    public function isWhitelisted($name)
    {
        if (empty($this->whitelist) || isset($this->whitelist[$name])) {
            return true;
        }

        return false;
    }
}