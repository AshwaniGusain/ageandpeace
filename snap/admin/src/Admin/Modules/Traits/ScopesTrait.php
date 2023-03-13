<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\ScopesService;
use Snap\Admin\Ui\Components\Scopes;
use Snap\Ui\UiComponent;

trait ScopesTrait
{
    public function registerScopesTrait()
    {
        $this->aliasTrait('scopes', 'Snap\Admin\Modules\Traits\ScopesTrait');
    }

    public function bootScopesTrait()
    {
        $this->bindService('scopes', function(){
            return ScopesService::make($this);
        });

        $this->addUiCallback(Scopes::class, function ($ui, $request, $module) {
            $service = $this->service('scopes');
            if (!empty($this->scaffoldScopes)) {
                $service->scaffold($this->scaffoldScopes);
            }
            $ui
                ->setActive($service->active)
                ->setScopes($service->scopes)
            ;

            $tableService = $this->service('table');
            if ($tableService) {
                $ui->setPaginationLimit($tableService->limit);
            }

            $service->query();

        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function scopes(ScopesService $search, Request $request)
    {

    }
}