<?php

namespace Snap\Cache\Ui;

use Snap\Admin\Ui\BasePage;

class CacheIndexPage extends BasePage
{
    protected $view = 'cache::cache';

    protected $data = [
        ':heading' => '\Snap\Admin\Ui\Components\Heading[module]',
        ':alerts'  => '\Snap\Ui\Components\Bootstrap\Alerts',
        'trans:message' => 'cache::messages.cache_message',
    ];

    public function initialize()
    {
        $this->heading
            ->setTitle(trans('cache::messages.cache_title'))
        ;

        $this->setPageTitle([trans('cache::messages.cache_title')]);
    }

}