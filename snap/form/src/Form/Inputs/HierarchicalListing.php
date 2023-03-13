<?php

namespace Snap\Form\Inputs;

class HierarchicalListing extends BaseInput
{
    protected $view = 'form::inputs.hierarchical-listing';
    protected $scripts = [
        'assets/snap/js/components/form/HierarchicalListingInput.js',
        'assets/snap/js/components/resource/HierarchicalListing.js',
        'assets/snap/vendor/jquery-ui/sortable.js',
        'assets/snap/vendor/jquery/plugins/jquery.mjs.nestedSortable.js',
    ];
    protected $data = [
        'module' => null,
        'filters' => [],
    ];

    public function initialize()
    {
        $this->label->setText(false);

        if (empty($this->module)) {
            $module = str_singular($this->key);
            if (\Admin::modules()->has($module)) {
                $this->setModule($module);
            }
        }
    }

    public function setModule($module)
    {
        if (is_string($module)) {
            $module = \Admin::modules()->get($module);
        }
        $this->data['module'] = $module;

        return $this;
    }


    protected function _render()
    {
        $listingUrl = $this->module->url('listing');
        $this->data['filters']['inline'] = 1;

        $listingUrl .= '?' . http_build_query($this->filters);
        $createUrl = $this->module->url('create_inline');

        $this->setAttrs(
            [
                'create-url' => $createUrl,
                'listing-url'  => $listingUrl,
            ]
        );

        return parent::_render();
    }


}