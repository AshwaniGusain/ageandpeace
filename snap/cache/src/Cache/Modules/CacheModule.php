<?php

namespace Snap\Cache\Modules;

use Form;
use Snap\Admin\Modules\Module;
use Snap\Cache\Http\Controllers\CacheController;

class CacheModule extends Module
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;

    protected $parent = null;

    protected $handle = 'cache';

    protected $name = 'Cache';

    protected $pluralName = 'Cache';

    protected $menuParent = 'tools';

    protected $menuLabel = 'Clear Cache';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-file-o';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    //protected $permissions = [];
    protected $config = null;

    protected $routes = [];

    protected $controller = CacheController::class;

    public function register()
    {
        parent::register();
        $this->addRoute(['post'], '/', '@doClear', ['as' => 'clear']);
    }
}