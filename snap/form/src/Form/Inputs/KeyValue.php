<?php

namespace Snap\Form\Inputs;

use Snap\Support\Helpers\ArrayHelper;
use Snap\Support\Helpers\UtilHelper;
use Snap\Ui\Traits\VueTrait;
use Snap\Ui\Traits\AttrsTrait;

class KeyValue extends BaseInput
{
    use VueTrait;
    use AttrsTrait;

    protected $vue = 'snap-keyvalue-input';

    protected $scripts = [
        'assets/snap/js/components/form/KeyValueInput.js',
    ];

    protected $view = 'form::inputs.input';
    protected $data = [
        'attrs' => [
        ],
    ];

    protected $convertToJson = true;

    public function initialize()
    {
        $this->setPostProcess(function($value, $input, $request, $resource){
            $request->merge([$this->key => $this->processData($value)]);
            //$request->attributes->add([$this->key => $this->processData($value)]);
            //$request->request->set($this->key, $this->processData($value));
            //dump($request->input($this->key));
            //dump($request->get($this->key));
        }, 'beforeValidation');
    }

    protected function processData($value)
    {
        if ($this->convertToJson && is_array($value)) {
            $data = [];
            foreach ($value as $key => $val) {
                $data[$key] = $val;

                //$data[] = ['key' => $key, 'value' => $val];
            }
            $value = json_encode($data, JSON_FORCE_OBJECT);
            //$value = ($data);
        }

        return $value;
    }

    public function convertToJson(bool $convert)
    {
        $this->convertToJson = $convert;

        return $this;
    }

    protected function _render()
    {
        //$this->data['value'] = $this->getValue();

        //$this->setAttrs(
        //    [
        //        'name'  => $this->getName(),
        //        'id'    => $this->getId(),
        //
        //        //'class' => 'form-control',
        //        ':value' => $this->getValue() // For Vue.js component
        //    ]
        //);

        $this->setAttrs([
            'name'  => $this->getName(),
            ':value' => $this->getValue(),
            'id'    => $this->getId(),
            'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class') : '',
        ]);


        return parent::_render();
    }
    //
    //public function setValue($value)
    //{
    //    // Detect if it is just a comma separated list and if so, convert it into an array so we can properly encode it.
    //    if (is_string($value) && ! UtilHelper::isJson($value)) {
    //        $value = ArrayHelper::normalize($value);
    //    }
    //
    //    $this->value = json_encode($value);
    //
    //    return $this;
    //}

}