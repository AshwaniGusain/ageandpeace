<?php

namespace Snap\Admin\Ui\Components;

use Snap\Admin\Ui\Module\Resource\EditPage;
use Snap\Ui\Components\Bootstrap\ButtonLink;
use Snap\Ui\Components\Bootstrap\Card;

class RelatedPanel extends Card
{
    protected $data = [
        'module' => null,
    ];

    protected function _render() {

        if ($this->resource) {
            if (!$this->parent instanceof EditPage && $this->module->hasService('form') && $this->module->hasPermission('edit')) {
                $this->list->add(new ButtonLink(['label' => trans('admin::resources.edit'), 'icon' => 'fa fa-edit','href' => $this->module->url('edit', ['resource' => $this->resource->id]), 'type' => 'primary', 'class' => 'd-block']));
            } elseif ($this->parent instanceof EditPage && $this->module->hasService('show')) {
                $this->list->add(new ButtonLink(['label' => trans('admin::resources.view'), 'icon' => 'fa fa-eye', 'href' => $this->module->url('show', ['resource' => $this->resource->id]), 'type' => 'secondary', 'class' => 'd-block']));
            }
            if ($this->resource->lastUpdatedBy) {
                $this->list->add(trans('admin::resources.updated_by', ['user' => $this->resource->lastUpdatedBy->name]));
            }
            if ($this->resource->created_at) {
                $this->list->add(trans('admin::resources.created_at', ['date' => $this->resource->created_at->format(\Admin::config('datetime_format'))]));
            }
            if ($this->resource->updated_at) {
                $this->list->add(trans('admin::resources.updated_at', ['date' => $this->resource->updated_at->format(\Admin::config('datetime_format'))]));
            }
            if ($this->resource->deleted_at) {
                $this->list->add(trans('admin::resources.deleted_at', ['date' => $this->resource->deleted_at->name]));
            }
        }

        return parent::_render();
    }
}