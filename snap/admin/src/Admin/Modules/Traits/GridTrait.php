<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\GridService;
use Snap\Ui\UiComponent;

trait GridTrait
{
    public function registerGridTrait()
    {
        $this->aliasTrait('grid', 'Snap\Admin\Modules\Traits\GridTrait');

        $this->addRoute(['get'], 'grid', '@grid', ['as' => 'grid']);
    }

    public function bootGridTrait()
    {
        $this->bindService('grid', function () {
            return GridService::make($this);
        });

        $this->addUiCallback('grid', function ($ui, $request) {
            $service = $this->service('grid');
            //$this->grid($service, $request);
            $ui->grid
                ->setLimit($service->limit)
                ->setCols($service->cols)
                ->setLimitOptions($service->pagination->limits)
            ;
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function grid(GridService $grid, Request $request)
    {

    }
}