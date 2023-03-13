<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\TableService;
use Snap\Ui\UiComponent;

trait TableTrait
{

    public function registerTableTrait()
    {
        $this->aliasTrait('table', 'Snap\Admin\Modules\Traits\TableTrait');
        $this->addRoute(['get'], 'table', '@table', []);
    }

    public function bootTableTrait()
    {
        $this->bindService('table', function(){
            return TableService::make($this);
        }, false);

        $this->addUiCallback('table', function ($ui, $request) {
            $service = $this->service('table')->setTable($ui->table);

            $this->table($service, $request);

            if (property_exists($this, 'tableColumns')) {
                $service->columns = $this->tableColumns;
            }

            $ui->table
                ->setFormatters($service->formatters)
                ->setColumns($service->columns)
                ->setSort($service->sort)
                ->setLimit($service->limit)
                ->setActions($service->actions)
                ->setLimitOptions($service->pagination->limits)
                ->setSortable($service->sortable)
                ->setNonSortable($service->nonSortable)
                ->setIgnored($service->ignored)
                ->setCustomSort($service->customSort)
            ;

        }, UiComponent::EVENT_INITIALIZING);
    }

    protected function table(TableService $table, Request $request)
    {

    }

}