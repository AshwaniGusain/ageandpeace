<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Form\Inputs\BaseInput;
use Snap\Ui\UiComponent;

/*
$relatedInfo = RelatedInfoService::make();
$relatedInfo->add('This is text');
*/
class RelatedInfoService
{
    public $items = [];
    public $resource;
    public $panel;
    public $inputs;

    protected $module;
    protected $request;

    public function __construct($module, Request $request, $panel = null)
    {
        $this->module = $module;
        $this->request = $request;
        $this->panel = $panel;
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function resource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function panel($panel)
    {
        if ($panel) {
            $this->panel = $panel->visible(true);
        }


        return $this;
    }

    public function inputs($inputs)
    {
        $inputs = is_array($inputs) ? $inputs : func_get_args();

        foreach ($inputs as $input) {
            $this->add($input);
            if ($input instanceof BaseInput) {
                $this->inputs[$input->key] = $input;
            }
        }
    }

    public function moveInputs($inputs = [])
    {
        $inputs = is_array($inputs) ? $inputs : func_get_args();

        $formService = $this->module->service('form')->resource($this->resource);

        $remove = [];
        if ($formService) {
            foreach ($inputs as $key) {
                $input = $formService->get($key);
                if ($input) {
                    $this->add($input);

                    $formService->remove($remove);
                    $remove[] = $key;
                }
            }
        }

        // We remove the form elements in this event so that it doesn't effect other areas that rely on the form service (like inline editing).
        $this->module->addUiCallback(['edit', 'create', 'show'], function ($ui, $request) use ($formService, $remove) {
            $formService->remove($remove);

        }, UiComponent::EVENT_BEFORE_RENDER);

        return $this;
    }

    public function add($item)
    {
        if ($this->panel) {
            $this->panel->list->add($item);
        }

        return $this;
    }

    public function __call($method, $args)
    {
        if (isset($this->panel)) {
            return call_user_func_array([$this->panel, $method], $args);
        }
    }

    public function __get($prop)
    {
        if (isset($this->panel->{$prop})) {
            return $this->panel->{$prop};
        }
    }
}