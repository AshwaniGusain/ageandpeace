<?php

namespace Snap\Page\Ui\Pages;

use DB;
use Snap\Ui\UiComponent;

class PublicTaxonomyPage extends UiComponent
{
    protected $view = 'page::pages.module.taxonomy';

    protected $data = [
        'module' => null,
        'models' => null,
        'term' => null,
        'limit' => 10,
    ];

    protected function _render()
    {
        $models = $this->term->{$this->module}->paginate($this->limit);

        $this->setModels($models);

        return parent::_render();
    }
}