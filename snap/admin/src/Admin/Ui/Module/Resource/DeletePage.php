<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Support\Helpers\ArrayHelper;
use Snap\Admin\Ui\BasePage;

class DeletePage extends BasePage
{
    protected $view = 'admin::module.resource.delete';
    protected $data = [
        ':heading'       => '\Snap\Admin\Ui\Components\Heading[module]',
        'form_component' => 'snap-form',
        'resources' => [],
        'ids'       => [],
    ];

    public function initialize()
    {
        $this->heading
            ->setTitle(trans('admin::resources.delete'))
            ->setCreate(false)
            ->setBack($this->module->url())
            //->setBack(url()->previous())
        ;

        $this->setPageTitle([trans('admin::resources.delete'), $this->module->pluralName()]);
    }

    public function setIds($ids)
    {
        if (is_string($ids)) {
            $ids = ArrayHelper::normalize($ids);
        }

        $model = $this->module->getModel();
        $this->resources = $model->withoutGlobalScopes()->whereIn($model->getKeyName(), $ids)->get();

        $this->data['ids'] = $ids;

        return $this;
    }

}