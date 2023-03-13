<?php

namespace Snap\Admin\Ui\Components\Inputs\Traits;

use Illuminate\Support\Facades\Event;
use Snap\Admin\Modules\ModuleModel;
use Snap\Admin\Modules\ResourceModule;

trait HasModule
{
    public function bootHasModule() {
        $this->addScript([
            'assets/snap/js/components/form/CreateEdit.js',
        ]);

        $this->data['module'] = null;
        $this->data['url_params'] = null;

        //$this->setView('admin::components.inputs.module-select');


        $this->setId($this->name);

        if (empty($this->module)) {

            if (!empty($this->model)) {
                $module = strtolower(class_basename($this->model));
            } else {
                $module = str_replace('_id', '', $this->name);
            }

            if (\Admin::modules()->has($module)) {
                $this->setModule($module);
            }
        }

        if (empty($this->cast) && $this->module) {
            $this->cast = function($value){
                $model = $this->module->getModel();
                return $model->find($value);
            };
        }

        Event::listen([static::eventName(static::EVENT_BEFORE_RENDER)], function($ui) {
            $this->_modulePreRender();
        });
    }

    public function setModule($module)
    {
        if (is_string($module)) {
            $module = \Admin::modules()->get($module);
        }

        //@TODO... get this to work with permissions.
        //if ($module && $module->hasPermission('permission:admin actions')){
        $this->data['module'] = $module;
        if ($module) {
            $this->setModel($module->getModel());
        }

        //}

        return $this;
    }

    public function setUrlParams($params)
    {
        if (is_array($params)) {
            $params = http_build_query($params);
        }

        $this->params = $params;

        return $this;
    }

    protected function _modulePreRender()
    {
        $activeModule = \Admin::modules()->current();

        if (! $this->hasOptions() && $this->module instanceof ResourceModule) {
            $model = $this->module->getModel();
            $listKey = ($model instanceof ModuleModel) ? $model->getDisplayNameKey() : $model->getKeyName();
            $options = $model->lists($listKey);
            $this->setPlaceholder(true);
            $this->setOptions($options);
        }

        $this->with([
            'module_url' => ($this->module) ? $this->module->url() : null,
            'active_url' => ($activeModule) ? $activeModule->url() : null,
            'url_params' => $this->url_params,
        ]);

        return parent::_render();
    }
    /*protected function _render()
    {
        $activeModule = \Admin::modules()->current();

        if (! $this->hasOptions() && $this->module instanceof ResourceModule) {
            $model = $this->module->getModel();
            $listKey = ($model instanceof ModuleModel) ? $model->getDisplayNameKey() : $model->getKeyName();
            $options = $model->lists($listKey);
            $this->setPlaceholder(true);
            $this->setOptions($options);
        }

        $this->with([
            'module_url' => ($this->module) ? $this->module->url() : null,
            'active_url' => ($activeModule) ? $activeModule->url() : null,
            'url_params' => $this->url_params,
        ]);

        return parent::_render();
    }*/

}