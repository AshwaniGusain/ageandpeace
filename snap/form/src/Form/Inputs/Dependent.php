<?php

namespace Snap\Form\Inputs;

use Snap\Form\Fields\Traits\HasOptions;
use Snap\Ui\Traits\VueTrait;
use Snap\Ui\Traits\AttrsTrait;

class Dependent extends BaseInput
{
    use VueTrait;
    use AttrsTrait;
    use HasOptions;

    protected $vue = 'snap-dependent-input';

    protected $scripts = [
        'assets/snap/vendor/select2/js/select2.min.js',
        'assets/snap/js/components/form/DependentInput.js',
    ];

    protected $view = 'form::inputs.vue';
    protected $data = [
        'attrs' => [
        ],
        'placeholder' => null,
        'multiple' => false,
    ];

    protected function _render()
    {
        $this->data['value'] = $this->getValue();

        $this->with(
            [
                'options' => $this->getOptions(),
            ]
        );

        $this->setAttrs(
            [
                'value'  => $this->getValue(),
                'name'     => $this->getName(),
                'id'       => $this->getId(),
                'multiple' => $this->getMultiple(),
                'class'    => $this->getAttr('class') ? $this->getAttr('class') : '',
            ]
        );


        return parent::_render();
    }

    public function setSource($source)
    {
        $this->setAttr('source', $source);

        return $this;
    }

    public function getSource()
    {
        return $this->getAttr('source');
    }

    public function setParams($params)
    {
        $this->setAttr('params', $params);

        return $this;
    }

    public function getParams()
    {
        return $this->getAttr('params');
    }

    public function setUrl($url, $params = [])
    {
        $this->setAttr('url', $url);
        if ($params) {
            $this->setUrlParams($params);
        }

        return $this;
    }

    public function getUrl()
    {
        return $this->getAttr('url');
    }

    public function setUrlParams($params = [])
    {
        $this->setAttr('url-params', $params);

        return $this;
    }

    public function getUrlParams()
    {
        return $this->getAttr('url-params');
    }
}