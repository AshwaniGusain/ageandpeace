<?php

namespace Snap\Admin\Ui\Components;

use Illuminate\Http\Request;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class Filters extends UiComponent
{
    use JsTrait;

    protected $view = 'admin::components.filters';

    protected $scripts = [
        'assets/snap/js/components/resource/Filters.js',
    ];

    protected $data = [
        'module' => null,
        'form'   => null,
        'show'   => false,
    ];

    public $filters;
    public $values;

    public function initialize(Request $request)
    {

        //$this->filters = new FilterManager($this->module->getQuery(), $request);

        // Only display this if the module trait "filters" is used.
        if (! $this->module->hasTrait('filters')) {
            $this->setVisible(false);
        } else {
            //$this->form = $this->module->getFiltersForm();
            $this->values = $request->all();
            $this->parent()->buttons->add('<i class="fa fa-filter" title="' . trans('admin::resources.filters') . '"></i>', [
                    'attrs' => [
                        'class'         => 'btn btn-secondary btn-icon',
                        'data-toggle'   => 'collapse',
                        'href'          => '#snap-resource-filters',
                        'aria-expanded' => 'false',
                    ],
                ]);
        }
    }
    //
    //public function add($key, $method = 'where', $operator = '=')
    //{
    //    $this->filters->add($key, $method, $operator);
    //
    //    return $this;
    //}

    protected function _render()
    {
        $this->form = $this->filters->getForm();
        $this->form->withValues($this->values);
        $this->form->addSubmit(trans('admin::resources.filter_submit'), ['size' => 'sm']);
        $this->form->addButtonLink(trans('admin::resources.filter_reset'), [
            'href' => $this->module->url('table'),
            'size' => 'sm',
        ]);

        $this->data['fields'] = $this->form->inputs();
        $this->data['groups'] = $this->form->groups();
        $this->data['buttons'] = $this->form->buttons();
        $this->data['module'] = $this->module;
        $this->data['show'] = $this->show;

        return parent::_render();
    }
}