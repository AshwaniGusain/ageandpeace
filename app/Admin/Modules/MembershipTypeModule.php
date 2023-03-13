<?php

namespace App\Admin\Modules;

use App\Models\MembershipType;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FormService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Support\Helpers\NumberHelper;

class MembershipTypeModule extends ResourceModule
{
	use \Snap\Admin\Modules\Traits\FormTrait;
	use \Snap\Admin\Modules\Traits\TableTrait;
	use \Snap\Admin\Modules\Traits\NavigableTrait;
	use \Snap\Admin\Modules\Traits\SearchTrait;
	//use \Snap\Admin\Modules\Traits\FilterableTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;
	// use \Snap\Admin\Modules\Traits\LogTrait;
	// use \Snap\Admin\Modules\Traits\RestorableTrait;
	use \Snap\Admin\Modules\Traits\DeletableTrait;
	// use \Snap\Admin\Modules\Traits\ExportableTrait;
	// use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
	// use \Snap\Admin\Modules\Traits\ViewableTrait;

	protected $parent = null;
	protected $handle = 'membership_type';
	protected $name = 'MembershipType';
	protected $pluralName = 'Membership Types';
	protected $menuParent = 'admin';
	protected $menuLabel = 'Membership Types';
	protected $description = '';
	protected $version = '1.0.0';
	protected $icon = 'fa fa-list-ol';
	protected $path = __DIR__;
	protected $modules = [];
	protected $uri = null;
	//protected $permissions = [];
	protected $config = null;
	protected $routes = [];
	//protected $controller = '\App\Admin\Controllers\MembershipTypeController';
	protected $model = MembershipType::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->columns(['id',
                       'tier',
                       'price',
                       'term_length',
                       'active',
                    ])
            ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::currencyFormatter', ['price'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['tier', 'price', 'term_length', 'active'])
        ;
    }

    public function form(FormService $form, Request $request)
    {
        $form->scaffold();
    }
}