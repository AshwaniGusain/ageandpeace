<?php

namespace App\Admin\Modules;

use App\Models\Company;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FormService;
use Snap\Admin\Modules\Services\IndexableService;
use Snap\Admin\Modules\Services\TableService;

class CompanyModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\DuplicateTrait;
    use \Snap\Admin\Modules\Traits\IndexableTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    protected $parent = null;

    protected $handle = 'company';

    protected $name = 'Company';

    protected $pluralName = 'Companies';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Companies';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-building-o';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = Company::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->columns(['name', 'slug', 'updated_at'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['name'])
        ;
    }

    public function form(FormService $form, Request $request)
    {
        $form
            ->scaffold()
            //->get('providers')->setUpdateResetValue(null)
        ;
    }

    protected function indexable(IndexableService $indexable, Request $request)
    {
        $indexable->fields(['name', 'slug']);
    }

}