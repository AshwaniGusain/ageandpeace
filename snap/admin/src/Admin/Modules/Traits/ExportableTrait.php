<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\ExportableService;
use Snap\Ui\UiComponent;

trait ExportableTrait
{
    public function registerExportableTrait()
    {
        $this->aliasTrait('delete', 'Snap\Admin\Modules\Traits\ExportableTrait');
        $this->addRoute(['get'], 'export', '@export', ['as' => 'export']);
    }

    public function bootExportableTrait()
    {
        $this->bindService('export', function(){
            return ExportableService::make($this);
        });

        $this->addUiCallback(['table'], function ($ui, $request, $module) {
            $ui->dropdown->add($module->url('export').'?'.$request->getQueryString(), 'Export');
        }, UiComponent::EVENT_INITIALIZED);
    }

    public function export(ExportableService $export, Request $request)
    {

    }

}