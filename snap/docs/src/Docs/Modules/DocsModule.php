<?php

namespace Snap\Docs\Modules;

use Snap\Admin\Modules\Module;

class DocsModule extends Module
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;

    protected $parent = null;
    protected $handle = 'docs';
    protected $name = 'Docs';
    protected $pluralName = 'Docs';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Docs';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-book';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    protected $permissions = ['view docs'];
    protected $config = null;
    protected $routes = [];
    protected $controller = '\Snap\Docs\Http\Controllers\DocsController';
    protected $ui = [
        //'docs'     => 'module.dashboard',
    ];

    public function register()
    {
        parent::register();
        $this->addRoute(['get'], 'auto', '@classDoc', ['as' => 'classDoc']);
        $this->addRoute(['get'], '{package}/{page?}', '@package', ['as' => 'package', 'where' => ['package' => '[\w,-\.]+', 'page' => '.+']]);
    }

}