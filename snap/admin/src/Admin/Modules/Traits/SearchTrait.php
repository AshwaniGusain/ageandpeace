<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\SearchService;
use Snap\Admin\Ui\Components\Search;
use Snap\Ui\UiComponent;

trait SearchTrait
{
    protected $searched = false;

    public function registerSearchTrait()
    {
        $this->aliasTrait('search', 'Snap\Admin\Modules\Traits\SearchTrait');
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function bootSearchTrait()
    {
        $this->bindService('search', function(){
            return SearchService::make($this);
        });

        $this->addUiCallback(Search::class, function ($ui, $request, $module) {
            $this->service('search')->query();

        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function search(SearchService $search, Request $request)
    {

    }

}