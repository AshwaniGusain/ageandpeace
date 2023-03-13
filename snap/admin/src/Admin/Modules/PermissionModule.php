<?php

namespace Snap\Admin\Modules;

use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Models\Permission;
use Snap\Admin\Models\Role;
use Snap\Form\Processors\RelationshipToManyProcessor;

class PermissionModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;

    protected $parent = 'user';
    protected $handle = 'permission';
    protected $name = 'Permission';
    protected $pluralName = 'Permissions';
    protected $menuParent = 'user';
    protected $menuLabel = 'Permissions';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = '';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    protected $permissions = [];
    protected $config = null;
    protected $routes = [];
    protected $controller = ResourceModuleController::class;
    protected $model = Permission::class;
    protected $scopes = ['active', 'inActive', 'onlyTrashed'];


    public function table($table, Request $request)
    {
        $table
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['name', 'guard_name'])
        ;
    }

    public function form($form, Request $request)
    {
        $form->scaffold([
            'props' => ['roles' => ['type' => 'multiselect', 'options' => Role::where('name', '!=', 'super-admin')->lists('name'), 'post_process' => RelationshipToManyProcessor::class]],
            'except' => ['permissions', 'users']]
        );
    }
}