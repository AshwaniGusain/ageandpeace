<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\ListingService;
use Snap\Ui\UiComponent;

trait ListingTrait {
    /**
     *
     */
    public function registerListingTrait()
    {
        $this->aliasTrait('listing', 'Snap\Admin\Modules\Traits\ListingTrait');

        $this->addRoute(['get'], 'listing', '@listing', []);
        $this->addRoute(['post'], 'sort', '@sort', ['as' => 'sort']);
    }

    public function bootListingTrait()
    {
        $this->bindService('listing', function(){
            return ListingService::make($this);
        });

        $this->addUiCallback(['listing'], function ($ui, $request, $module) {
            $service = $this->service('listing');
            $ui->listing
                ->setItems($service->getItems())
                ->setIdColumn($service->idColumn)
                ->setParentColumn($service->parentColumn)
                ->setPrecedenceColumn($service->precedenceColumn)
                ->setSortable($service->sortable)
                ->setUpdateUri($service->updateUri)
                ->setNestingDepth($service->nestingDepth)
                ->setRootValue($service->rootValue)
                ->setItemTemplate($service->itemTemplate)
                ->setGroupBy($service->groupBy)
            ;

        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function listing(ListingService $listing, Request $request)
    {

    }

}