<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\Services\RestorableService;
use Snap\Admin\Ui\Components\RestoreVersions;
use Snap\Admin\Ui\Module\Resource\EditPage;
use Snap\Form\Fields\SelectInput;
use Snap\Ui\UiComponent;

trait RestorableTrait {

    public function registerRestorableTrait()
    {
        $this->aliasTrait('restorable', 'Snap\Admin\Modules\Traits\RestorableTrait');
        $this->addRoute(['get'], '{resource}/compare/{version}', '@compare', ['as' => 'compare', 'where' => ['resource' => '[0-9]+', 'version' => '[0-9]+']]);
        $this->addRoute(['post'], '{resource}/restore', '@restore', ['as' => 'doRestore', 'where' => ['resource' => '[0-9]+']]);
    }

    public function bootRestorableTrait()
    {
        $this->getModel()->saved(function($model) {
            if ($model instanceof RestorableInterface) {
                $model->archive();
            }
        });

        $this->bindService('restore', function(){
            return RestorableService::make($this);
        }, false);

        $this->addUiCallback('edit', function ($ui) {
            $resource = $ui->resource;
            $versions = $resource->versionsList();

            if ($versions->count()) {
                $versionsDropdown = new RestoreVersions();
                $versionsDropdown->setResource($resource)->setVersions($versions);
                $ui->related_panel->list->add($versionsDropdown);
            }
        }, UiComponent::EVENT_INITIALIZED);
    }

    public function restore(RestorableService $restore, Request $request)
    {

    }
}