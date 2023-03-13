<?php

namespace Snap\Admin\Ui;

use Illuminate\Http\Request;
use Snap\Admin\Ui\Layout\Menu;
use Snap\Ui\UiComponent;

class BasePage extends UiComponent
{
    protected $data = [
        'module'             => null,
        'config:page_title'  => 'snap.admin.title',
        'config:admin_title' => 'snap.admin.title',
        'js_config'          => [
            'el'          => '#snap-admin',
            'urlPaths'    => ['admin' => '/admin'],
            'environment' => 'testing',
            'debug'       => [
                'level' => 3,
                'clear' => false // need for Safari
            ],
        ],
        'object:menu'        => Menu::class,
        'object:request'     => Request::class,
        'inline'             => false,
    ];

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->data['js_config']['urlPaths'] = [
            'admin' => admin_url(),
        ];

        if ($module = \Admin::modules()->current()) {
            $this->data['js_config']['urlPaths']['module'] = $module->url();
        }

        $this->data['js_config']['environment'] = app('env');
    }

    public function isInline(): bool
    {
        return (bool) $this->inline;
    }

    public function setPageTitle($title)
    {
        if (is_string($title)) {
            $title = [$title];
        }

        array_push($title, config('snap.admin.title'));
        $title = implode(' : ', array_filter($title));

        $this->data['page_title'] = $title;

        return $this;
    }
}