<?php

namespace Snap\Admin\Modules\Services;

use Auth;
use Illuminate\Http\Request;
use Snap\Admin\Models\Log;

/*
$log = LogService::make();
 * */

class LogService
{
    public $message;

    public $data;

    public $level = 'info';

    public $enabled = true;

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

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    public function level($level)
    {
        $this->level = $level;

        return $this;
    }

    public function enable($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function save($resource)
    {
        if ($this->enabled) {
            $log = new Log();
            $log->message = $this->getMessage($resource);
            $log->data = $this->getData($resource);
            $log->level = $this->getLevel($resource);
            $log->user_id = Auth::user()->id;

            return $log->save();
        }

        return null;
    }

    protected function getData($resource)
    {
        if ($this->data instanceof \Closure) {
            return call_user_func($this->data, $resource);
        } else {

            if (is_object($resource) && method_exists($resource, 'toArray')) {
                $data = $resource->toArray();
            } else {
                $data = $resource;
            }

            // If the specified service data parameter is an array,
            // we'll limit the data stored to just those array keys.
            if (is_array($this->data)) {
                $data = collect($data)->only($this->data)->toArray();
            }

            if (is_array($data)) {
                $data = json_encode($data);
            }

            return $data;
        }
    }

    protected function getLevel($resource)
    {
        if ($this->level instanceof \Closure) {
            return call_user_func($this->level, $resource);
        } else {

            return $this->level;
        }
    }

    protected function getMessage($resource)
    {
        if ($this->message instanceof \Closure) {
            return call_user_func($this->message, $resource);
        } else {
            $localKey = ($this->message) ?? 'admin::resources.log';

            return trans($localKey, [
                'module' => $this->module->name(),
                'name'   => $resource->display_name,
                'id'     => $resource->getKey(),
            ]);
        }
    }
}