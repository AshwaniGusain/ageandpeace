<?php

namespace App\Admin\Modules;

use App\Models\Company;
use App\Models\Provider;
use App\Models\ProviderType;
use App\Models\ProviderTypeGroup;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FiltersService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Admin\Modules\Traits\Filters\Filter;
use Snap\Support\Helpers\GoogleHelper;
use App\Models\Zip;

class ProviderTypeGroupModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    protected $parent = 'provider';

    protected $handle = 'provider_type_group';

    protected $name = 'Provider Type Group';

    protected $pluralName = 'Provider Type Groups';

    protected $menuParent = 'provider';

    protected $menuLabel = 'Provider Type Groups';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = '';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = ProviderTypeGroup::class;

    public function table(TableService $table, Request $request)
    {
        //$table
        //    ->defaultSort('user.name')
        //    ->columns(['user.name', 'user.email', 'zip', 'age', 'active', 'updated_at'])
        //    ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['user:active'])
        //;
    }


    public function form($form, Request $request, $resource)
    {
        $form
            ->scaffold()
            //->get('providerTypes')->setUpdateResetValue(null)
        ;
    }


}
