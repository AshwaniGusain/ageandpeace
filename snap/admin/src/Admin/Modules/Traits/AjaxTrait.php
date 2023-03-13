<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\AjaxService;

trait AjaxTrait {

    public function registerAjaxTrait()
    {
        $this->aliasTrait('ajax', 'Snap\Admin\Modules\Traits\AjaxTrait');

        $this->addRoute(['get'], 'ajax/{method}', '@ajax', ['as' => 'ajax']);
    }

    public function bootAjaxTrait(Request $request)
    {
        $service = AjaxService::make($this);
        $this->bindService('ajax', $service);

        //$this->ajax($service, $request);
    }

    protected function ajax(AjaxService $ajax, Request $request)
    {

    }

    public function ajaxOptions(Request $request)
    {
        return $this->getModel()->lists($this->getModel()->getDisplayNameKey());
    }

}