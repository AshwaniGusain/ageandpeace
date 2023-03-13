<?php

namespace Snap\Page\Ui\Inputs;

use Snap\Form\Inputs\BaseInput;
use Snap\Ui\Traits\VueTrait;

class Template extends BaseInput
{
    use VueTrait;

    protected $vue = 'snap-template-input';

    protected $scripts = [
        'assets/snap/js/components/form/TemplateInput.js',
    ];

    protected $view = 'page::inputs.template';
    protected $data = [
        'template' => null,
        'templates' => 'default',
        //'form' => null,
        'resource' => null,
        'meta_scope' => '',
    ];

    public function initialize()
    {
        //// Need to reflash because this is being ajaxed in.
        //// need a better solution for validating template meta data.
        //request()->session()->reflash();

        $this->setPostProcess(function($value, $input, $request, $resource){

            $this->processRelationship($request, $resource);

        }, 'beforeSave');
    }

    protected function processRelationship($request, $resource)
    {
        $relationship = 'meta';
        $meta = $request->get($this->meta_scope);

        if (!$resource->$relationship()->set($meta)->save()){

        }
    }

    //public function setTemplates($templates)
    //{
    //    static $origLabel;
    //    if (is_null($origLabel)) {
    //        $origLabel = $this->label;
    //    }
    //
    //    if (count($templates) <= 1) {
    //        $this->setLabel(false);
    //    } else {
    //        $this->setLabel($origLabel);
    //    }
    //    $this->data['templates'] = $templates;
    //
    //    return $this;
    //}

    public function getValue()
    {
        $value = $this->value;
        if (is_array($value) && array_key_exists('__value__', $value)) {
            return $value['__value__'];
        } else {
            return $value;
        }
    }

    protected function _render()
    {
        // @TODO Jackhackery here... This will load any javascript/css in template form but "There's got to be a better way!"
        static $loadedForm;
        if (is_null($loadedForm)) {
            $templates = \Template::all();

            foreach ($templates as $handle => $template) {
                $template->getForm();
            }

            $loadedForm = true;
        }

        $this->data['template_url'] = route('admin/page/template', ['']);
        if (is_string($this->templates)) {

            $this->templates = \Template::group($this->templates)->map(function ($item) {
                        return $item->name();
                    });
        }

        //$value = $this->getValue();
        //$this->data['value'] = is_array($value) ? json_encode($value) : $value;

        $this->data['value'] = $this->getValue();
        $this->data['name'] = $this->getName();
        $this->data['key'] = $this->getKey();

        //$this->data['resource_id'] = $this->resource ? (int) $this->resource->id : null;
        $this->data['resource_id'] = $this->resource ? (int) $this->resource->getKeyName() : null;

        return parent::_render();
    }
}