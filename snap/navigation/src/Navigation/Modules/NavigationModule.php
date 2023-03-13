<?php

namespace Snap\Navigation\Modules;

use Form;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Navigation\Http\Controllers\NavigationModuleController;
use Snap\Navigation\Models\Navigation;
use Snap\Navigation\Models\NavigationGroup;

class NavigationModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\ListingTrait;
    use \Snap\Admin\Modules\Traits\RestorableTrait;

    //use \Snap\Admin\Modules\Traits\FiltersTrait;

    protected $parent = null;

    protected $handle = 'navigation';

    protected $name = 'Navigation';

    protected $pluralName = 'Navigation';

    protected $menuParent = 'site';

    protected $menuLabel = 'Navigation';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-compass';

    protected $path = __DIR__;

    protected $modules = [
        NavigationGroupModule::class,
    ];

    protected $uri = null;

    //protected $permissions = [];
    protected $config = null;

    protected $routes = [];

    protected $model = Navigation::class;

    public function register()
    {
        parent::register();
    }

    public function table($table, Request $request)
    {
        $table->columns([
            'link',
            'label',
            'group.name' => 'Group',
            'active',
            'hidden',
            'created_at',
            'updated_at',
        ])
        ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['hidden'])
        ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['link', 'label', 'active', 'hidden'])
        ;
    }

    protected function form($form, Request $request, $resource = null)
    {
        $form
            ->scaffold()
            ->addMirror('key', ['bound_to' => 'link', 'required' => true])
            ->addSelect('language', ['options' => ['english'], 'hide_if_one' => true])
        ;
        $form
            ->get('group_id')->setModule('navigation.navigation-group')
        ;
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request)
    {
        $relatedInfo->moveInputs(['active']);
    }
}