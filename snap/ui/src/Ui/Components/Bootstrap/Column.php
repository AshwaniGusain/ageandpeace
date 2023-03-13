<?php

namespace Snap\Ui\Components\Bootstrap;

use Snap\Support\Helpers\ArrayHelper;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Column extends UiComponent
{
    use CssClassesTrait;

    protected $view = 'component::bootstrap.column';

    protected $data = [
        'content' => null,
        'xs'      => null,
        'sm'      => null,
        'md'      => null,
        'lg'      => null,
        'class'   => null,
        'id'      => null,
    ];

    public function getClass()
    {
        $classes = (! empty($this->data['class'])) ? ArrayHelper::normalize($this->data['class']) : [];
        if ($this->xs) {
            $classes[] = 'col-xs-'.$this->xs;
        }
        if ($this->sm) {
            $classes[] = 'col-sm-'.$this->sm;
        }
        if ($this->md) {
            $classes[] = 'col-md-'.$this->md;
        }
        if ($this->lg) {
            $classes[] = 'col-lg-'.$this->lg;
        }

        return implode(' ', array_filter($classes));
    }

    protected function _render()
    {
        $this->with([
            'class' => $this->getClass(),
        ]);

        return parent::_render();
    }
}