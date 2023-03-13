<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Admin\Ui\BasePage;

class FormPage extends BasePage
{
    protected $view = 'admin::module.resource.form';

    protected $data = [
        'resource'       => null, // <!-- Order matters here because it's needed for the form object below.
        ':heading'       => '\Snap\Admin\Ui\Components\Heading[module]',
        ':buttons'       => '\Snap\Admin\Ui\Components\FormButtonBar[module,resource]',
        ':alerts'        => '\Snap\Admin\Ui\Components\AlertMessages',
        ':form'          => '\Snap\Admin\Ui\Components\Form[module,resource]',
        ':related_panel' => '\Snap\Admin\Ui\Components\RelatedPanel[module,resource]',
        ':preview'       => '\Snap\Admin\Ui\Components\Preview[module]',
        'form_component' => 'snap-form',
    ];

    /**
     *
     */
    public function initialize()
    {
        $this->form->withValues(old());

        // We are going to alias form to the nested Form object so we don't have to do
        // $ui->form->form which looks silly.
        //$this->form = $this->form->form;
        //if ($this->form) {
            $this->form->setTemplate('admin::components.form');
        //}

        $this->preview->visible(false);
        //$this->related_panel->visible(false);

        $this->buttons->add(trans('admin::resources.btn_save'), ['id' => 'btn-save', 'type' => 'primary', 'class' => 'border']);

        if ($this->module) {

            $this->buttons->add(trans('admin::resources.btn_save_and_exit'), ['id' => 'btn-save-exit', 'type' => 'primary', 'class' => 'border']);

            if ($this->module->hasPermission('create')) {
                $this->buttons->add(trans('admin::resources.btn_save_and_create'), ['id' => 'btn-save-create', 'type' => 'primary', 'class' => 'border']);

                //if ($this->resource && $this->resource->getKey()) {
                //    $this->add(trans('admin::resources.btn_duplicate'), ['id' => 'btn-duplicate', 'type' => 'primary', 'class' => 'border', 'attrs' => ['href' => $this->module->url('duplicate', ['id' => $this->resource->id])]]);
                //}
            }
        }
    }

    protected function _render()
    {
        if (method_exists($this->resource,'trashed') && $this->resource->trashed()) {
            $deletedColumn = $this->resource->getUpdatedAtColumn();
            $this->alerts->add(trans('admin::resources.trash_warning', ['date' => $this->resource->{$deletedColumn}, 'url' => $this->module->url('recover')]), 'warning');
            $this->form->assign('display_value', true);
        }

        return parent::_render();
    }
}