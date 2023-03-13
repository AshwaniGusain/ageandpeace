<?php

namespace Snap\Admin\Modules;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Models\Media;
use Snap\DataTable\DataTable;
use Snap\Ui\Components\Bootstrap\Card;
use Snap\Ui\Components\Bootstrap\ListGroup;

class SearchModule extends Module
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;

    protected $parent = null;
    protected $handle = 'search';
    protected $name = 'Search';
    protected $pluralName = 'Search';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Search';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-search';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    protected $permissions = ['view search'];
    protected $config = null;
    protected $routes = [];
    protected $controller = '\Snap\Admin\Http\Controllers\SearchController';
    protected $defaultRouteMethod = 'results';
    protected $ui = [
        'search'     => 'module.search',
    ];



}