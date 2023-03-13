<?php

namespace Snap\Admin\Ui\Module\Resource;

use Illuminate\Http\Request;

class CalendarPage extends IndexPage
{
    protected $view = 'admin::module.resource.calendar';

    protected $data = [
        ':calendar'     => '\Snap\Admin\Ui\Components\CalendarList[module]',
        ':filters'      => '\Snap\Admin\Ui\Components\Filters[module]',
        ':scopes'       => '\Snap\Admin\Ui\Components\Scopes[module]',
    ];

    public function initialize(Request $request)
    {
        parent::initialize($request);
        $this->buttons->setActive('calendar');
    }
}