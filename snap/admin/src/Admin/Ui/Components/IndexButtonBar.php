<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\Components\Bootstrap\ButtonGroup;

class IndexButtonBar extends ButtonGroup
{
     protected $data = [
         'module' => null,
     ];

    // public function initialize()
    // {
    //     parent::initialize();
    // }

    protected function _render()
    {
//        if ($this->module->hasTrait('filters')) {
//            $this->add('<i class="fa fa-filter"></i>',
//             ['attrs' => [
//                 'href' => '#',
//                 'class' => 'btn btn-secondary btn-icon',
//                 'data-toggle' => 'collapse',
//                 'href' => '#fuel-resource-filters',
//                 'aria-expanded' => 'false',
//             ]]
//            );
//        }

        $displayButtons = [];
        $displayOptions = [
            'table'    => '<i class="fa fa-table" title="' . trans('admin::resources.table') . '"></i>',
            'listing'  => '<i class="fa fa-list" title="' . trans('admin::resources.listing') . '"></i>',
            'map'      => '<i class="fa fa-map-marker" title="' . trans('admin::resources.map') . '"></i>',
            'grid'     => '<i class="fa fa-th-large" title="' . trans('admin::resources.grid') . '"></i>',
            'calendar' => '<i class="fa fa-calendar" title="' . trans('admin::resources.calendar') . '"></i>',
        ];

        $i = 0;

        if ($this->module) {

            foreach ($this->module->getTraitAliases() as $alias => $trait) {

                if (isset($displayOptions[$alias])) {
                    $label = $displayOptions[$alias];
                    $url = ($i == 0) ? '' : $alias;
                    $displayButtons[$label] = [
                        'label' => $label, 
                        'attrs' => ['attrs' => ['href' => $this->module->url($url)], 'title' => $label], 
                        'alias' => $alias
                    ];
                    $i++;
                }
            }
        }
        

        if (count($displayButtons) > 1) {

            foreach ($displayButtons as $button) {
                $this->add($button['label'], $button['attrs'], $button['alias']);
            }
        }

        return parent::_render();
    }

}