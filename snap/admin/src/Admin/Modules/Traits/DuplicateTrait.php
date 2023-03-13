<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\DuplicateService;
use Snap\Admin\Modules\Services\ShowService;
use Snap\Ui\UiComponent;

trait DuplicateTrait
{
    public function registerDuplicateTrait()
    {
        $this->aliasTrait('duplicate', 'Snap\Admin\Modules\Traits\DuplicateTrait');
        $this->addRoute(['get'], '{resource}/duplicate', '@duplicate', ['permission' => 'create', 'as' => 'duplicate', 'where' => ['resource' => '[0-9]+']]);
        $this->addRoute(['get'], '{resource}/duplicate_inline', '@duplicateInline', ['permission' => 'create', 'as' => 'duplicate_inline', 'where' => ['resource' => '[0-9]+']]);
    }

    public function bootDuplicateTrait()
    {
        $this->bindService('duplicate', function(){
            return DuplicateService::make($this);
        }, true);

        $this->addUiCallback(\Snap\Admin\Ui\Module\Resource\EditPage::class, function ($ui, $request) {
            $resource = $ui->resource;
            $ui->buttons->add(trans('admin::resources.btn_duplicate'), ['id' => 'btn-duplicate', 'type' => 'primary', 'class' => 'border', 'attrs' => ['href' => $this->url('duplicate', ['resource' => $resource->id])]]);

        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function duplicate(DuplicateService $duplicate, Request $request)
    {
    }

}