<?php

namespace Snap\Admin\Ui\Module;

use Illuminate\Http\Request;
use Snap\Admin\Models\Search;
use Snap\Admin\Ui\BasePage;

class SearchResultsPage extends BasePage
{
    protected $view = 'admin::module.search';

    protected $data = [
        ':heading' => '\Snap\Admin\Ui\Components\Heading',
        'q' => '',
        'results' => [],
        'limit' => 10,
        'pagination' => null,
    ];

    public function initialize(Request $request)
    {
        $this->results = Search::results($this->q, $this->limit);

        $title = ($this->q) ? trans_choice('admin::search.heading', $this->results->count(), ['q' => $request->input('q')]) : trans('admin::search.missing_term');
        $this->heading->setTitle($title);

        $this->setPageTitle([trans('admin::search.page_title')]);
    }
}