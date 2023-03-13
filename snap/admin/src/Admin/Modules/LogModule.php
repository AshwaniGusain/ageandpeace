<?php

namespace Snap\Admin\Modules;

use App\Models\User;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Models\Log;
use Snap\Admin\Modules\Services\ShowService;
use Snap\Admin\Modules\Services\TableService;
use Snap\DataTable\DataTableFormatters;
use Snap\Form\Inputs\Text;

class LogModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\ShowTrait;
    //use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;

    protected $parent = null;
    protected $handle = 'log';
    protected $name = 'Log';
    protected $pluralName = 'Logs';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Logs';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-archive';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    protected $permissions = ['view log'];
    protected $config = null;
    protected $routes = [];
    protected $controller = ResourceModuleController::class;
    protected $model = Log::class;

    protected $scaffoldForm = true;

    protected function table(TableService $table, Request $request)
    {
        $table
            ->columns(['message', 'level', 'data', 'user.name', 'created_at'])
            ->format('Snap\DataTable\DataTableFormatters::jsonFormatter', ['data'])
            ->format('Snap\DataTable\DataTableFormatters::containerFormatter,100,600', ['data'])
        ;
    }

    protected function show(ShowService $show, Request $request)
    {
        $show->assign('required', false, ['level', 'message', 'data']);
        $show->get('data')->displayAs(function($value, $input){
            return '<pre>'.array_format($value, '<br>').'</pre>';
        });

        $show->get('user_id')->displayAs(function($value, $input){
            $user = User::find($value);
            return $user->name;
        });
    }

}