<?php

namespace Snap\Admin\Ui\Components\Inputs;

use Snap\Admin\Modules\ModuleModel;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Ui\Components\Inputs\Traits\HasModule;
use Snap\Form\Inputs\Select2;

class ModuleSelect extends Select2
{
    use HasModule;

    protected $view = 'admin::components.inputs.module-select';

    //protected $scripts = [
    //    'assets/snap/vendor/select2/js/select2.min.js',
    //    'assets/snap/js/components/form/Select2Input.js',
    //    'assets/snap/js/components/form/CreateEdit.js',
    //];
    //
    //protected $styles = [
    //    'assets/snap/vendor/select2/css/select2.min.css',
    //    'assets/snap/vendor/select2/css/select2-bootstrap4.min.css',
    //];

    public function initialize()
    {
        parent::initialize();
        $this->addScript([
            'assets/snap/js/components/form/CreateEdit.js',
        ]);
    }
    //
    //protected $data = [
    //    'module' => null,
    //    'url_params' => null,
    //];
    //
    //public function initialize()
    //{
    //    parent::initialize();
    //
    //    $this->setId($this->name);
    //
    //    if (empty($this->module)) {
    //
    //        if (!empty($this->model)) {
    //            $module = strtolower(class_basename($this->model));
    //        } else {
    //            $module = str_replace('_id', '', $this->name);
    //        }
    //
    //        if (\Admin::modules()->has($module)) {
    //            $this->setModule($module);
    //        }
    //    }
    //
    //    if (empty($this->cast) && $this->module) {
    //        $this->cast = function($value){
    //            $model = $this->module->getModel();
    //            return $model->find($value);
    //        };
    //    }
    //}
    //
    //public function setModule($module)
    //{
    //    if (is_string($module)) {
    //        $module = \Admin::modules()->get($module);
    //    }
    //
    //    //@TODO... get this to work with permissions.
    //    //if ($module && $module->hasPermission('permission:admin actions')){
    //    $this->data['module'] = $module;
    //    if ($module) {
    //        $this->setModel($module->getModel());
    //    }
    //
    //    //}
    //
    //    return $this;
    //}
    //
    //public function setUrlParams($params)
    //{
    //    if (is_array($params)) {
    //        $params = http_build_query($params);
    //    }
    //
    //    $this->params = $params;
    //
    //    return $this;
    //}
    //
    //protected function _render()
    //{
    //    $activeModule = \Admin::modules()->current();
    //
    //    if (! $this->hasOptions() && $this->module instanceof ResourceModule) {
    //        $model = $this->module->getModel();
    //        $listKey = ($model instanceof ModuleModel) ? $model->getDisplayNameKey() : $model->getKeyName();
    //        $options = $model->lists($listKey);
    //        $this->setPlaceholder(true);
    //        $this->setOptions($options);
    //    }
    //
    //    //$this->setOptions()
    //    $this->with([
    //        'module_url' => ($this->module) ? $this->module->url() : null,
    //        'active_url' => ($activeModule) ? $activeModule->url() : null,
    //        'url_params' => $this->url_params,
    //    ]);
    //
    //    return parent::_render();
    //}
}