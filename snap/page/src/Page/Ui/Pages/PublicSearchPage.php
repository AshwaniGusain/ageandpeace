<?php

namespace Snap\Page\Ui\Pages;

use DB;
use Snap\Ui\UiComponent;

class PublicSearchPage extends UiComponent
{
    protected $view = 'page::pages.module.search';

    protected $data = [
        'model' => null,
        'models' => null,
        'term' => null,
        'limit' => 10,
        'scope' => 'search',
        'q' => '',
    ];

    protected function _render()
    {
        $models = $this->model->{$this->scope}($this->q)->paginate($this->limit);
        $this->setModels($models);


        return parent::_render();
    }
}