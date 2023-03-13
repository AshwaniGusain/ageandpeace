<?php

namespace Snap\Navigation\Modules;

use Form;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Navigation\Http\Controllers\NavigationModuleController;
use Snap\Navigation\Models\Navigation;
use Snap\Navigation\Models\NavigationGroup;

class NavigationGroupModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    //use \Snap\Admin\Modules\Traits\RestorableTrait;

    protected $parent = null;

    protected $handle = 'navigation-group';

    protected $name = 'Navigation Group';

    protected $pluralName = 'Navigation Groups';

    protected $menuParent = 'navigation';

    protected $menuLabel = 'Navigation Groups';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = '';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    //protected $permissions = [];
    protected $config = null;

    protected $routes = [];

    protected $controller = ResourceModuleController::class;

    protected $model = NavigationGroup::class;

    public function register()
    {
        parent::register();
    }

    public function table($table, Request $request)
    {
        $table->columns([
            'name',
            'active',
            'created_at',
            'updated_at',
        ])
        ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['name', 'active'])
        ;
    }

    protected function form($form, Request $request, $resource = null)
    {
        $form->scaffold();
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request)
    {

    }
}