<?php

namespace App\Admin\Modules;

use App\Models\Company;
use App\Models\Provider;
use App\Models\ProviderType;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FiltersService;
use Snap\Admin\Modules\Services\IndexableService;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Admin\Modules\Traits\Filters\Filter;
use Snap\Form\Inputs\SwitchInput;
use Snap\Support\Helpers\GoogleHelper;
use App\Models\Zip;

class ProviderTypeModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\IndexableTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    protected $parent = 'provider';

    protected $handle = 'provider_type';

    protected $name = 'Provider Type';

    protected $pluralName = 'Provider Types';

    protected $menuParent = 'provider';

    protected $menuLabel = 'Provider Types';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = '';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = ProviderType::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->defaultSort('name')
            ->columns(['name', 'slug', 'precedence', 'active', 'updated_at'])
            ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['user:active'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['name', 'slug', 'precedence', 'active'])
        ;
    }

    public function form($form, Request $request, $resource)
    {
        $form
            ->scaffold()
            //->get('providers')->setUpdateResetValue(null)
        ;

    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request, $resource = null)
    {
        $relatedInfo->moveInputs(['active']);
    }

    protected function indexable(IndexableService $indexable, Request $request)
    {
        $indexable->fields(['name']);
    }
}
