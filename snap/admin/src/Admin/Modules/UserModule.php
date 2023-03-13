<?php

namespace Snap\Admin\Modules;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Models\Role;
use Snap\Admin\Modules\Services\DeletableService;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Form\Processors\RelationshipToManyProcessor;

class UserModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\BasePermissionsSelectionTrait;
    //use \Snap\Admin\Modules\Traits\FiltersTrait;

    // use \Snap\Admin\Modules\Traits\LogTrait;
    // use \Snap\Admin\Modules\Traits\RestorableTrait;
    // use \Snap\Admin\Modules\Traits\ExportableTrait;
    // use \Snap\Admin\Modules\Traits\ViewableTrait;

    protected $parent = null;
    protected $handle = 'user';
    protected $name = 'User';
    protected $pluralName = 'Users';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Users';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-user';
    protected $path = __DIR__;
    protected $modules = [
        PermissionModule::class,
        RoleModule::class,
    ];
    protected $uri = null;
    //protected $permissions = [];
    protected $config = null;
    protected $routes = [];
    protected $controller = ResourceModuleController::class;
    protected $model = User::class;

    protected $scaffoldScopes = true;
    protected $allowForceDelete = true;

    public function query($query) {
        if (!\Auth()->user()->is_super_admin) {
            $superUserIds = User::role('super-admin')->pluck('id');
            $query->whereHas('roles', function($q) use ($superUserIds) {
                $q->whereNotIn('id', $superUserIds);
            });
        }
    }

    public function table($table, Request $request)
    {
        $table
            ->columns(['name', 'email', 'last_login_ip', 'last_login_at', 'created_at', 'updated_at', 'active', 'role_names' => 'Role', 'is_super_admin'])
            ->format('Snap\DataTable\DataTableFormatters::booleanFormatter', ['is_super_admin'])
            ->format('Snap\DataTable\DataTableFormatters::implodeFormatter', ['role_names'])
            // hasMany relationship which makes them tougher to sort
            //->nonSortable(['role_names', 'is_super_admin'])
            //->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['name', 'email', 'active'])
            // Customized inline editing so that super admins aren't touched
            ->format(function($columnData, $rowData, $key){
                if (in_array($key, ['name', 'email', 'active']) && empty($rowData['is_super_admin'])) {
                    return \Snap\Admin\Ui\Formatters\EditInline::format($columnData, $rowData, $key);
                }
                return $columnData;

            })->customSort(function($query, $col, $order, $limit) {
                $this->model->append(['is_super_admin', 'role_names']);
                $query->get()->sortBy($col, SORT_REGULAR, $order == 'desc')->take($limit)->values()->all();

                return $query->get()->sortBy($col, SORT_REGULAR, $order == 'desc')->take($limit)->values()->all();
            })
        ;

        // Setup custom delete actions for super admins
        $delete = function ($values) use ($table) {
            if (empty($values['deleted_at'])) {
                if (empty($values['is_super_admin'])) {
                    $url = ($table->inline) ? $values['id'].'/delete_inline' : $values['id'].'/delete';
                    return '<a href="'.$this->url($url).'" class="btn btn-sm btn-secondary table-action">'.__('admin::resources.action_delete').'</a>';
                }
            }

            return '';
        };

        // Don't display a checkbox for a super admin
        $checkboxes = function ($values) use ($table) {
            if (! $table->inline && empty($values['deleted_at']) && empty($values['is_super_admin'])) {
                return '<input type="checkbox" @click="$parent.toggleMultiSelect()" value="'.$values['id'].'" data-id="'.$values['id'].'" class="multiselect">';
            }
            return '';
        };

        $table->actions(['edit' => 'form', $delete, $checkboxes]);

    }

    public function form($form, Request $request, $resource = null)
    {
        $form
            ->scaffold()
            ->except(['last_login_ip', 'last_login_at', 'roles', 'permissions'])
            ->addPassword('password', ['order' => 1.5])
        ;

        // Admins don't have permissions so we won't display them.
        if ($resource && !$resource->hasRole('super-admin')) {
            $form
                ->addMultiselect('roles', ['options' => Role::where('name', '!=', 'super-admin')->lists('name'), 'post_process' => RelationshipToManyProcessor::class])
                ->addMultiCheckbox('permissions', ['label' => false, 'options' => $this->getPermissionOptions(), 'post_process' => RelationshipToManyProcessor::class], 'Permissions')
                ->move('permissions', 'after:roles')
            ;
        }

        // Super Admins can't be deactivated from UI.
        if ($resource && $resource->hasRole('super-admin')) {
            $form->remove(['active']);
        }
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request, $resource = null)
    {
        $relatedInfo->moveInputs(['active']);
    }

    protected function deletable(DeletableService $deletable, Request $request, $resource = null)
    {
        // Super admins can't be deleted!
        if ($resource) {
            $deletable->canDelete(!$resource->hasRole('super-admin'));
        }

    }

}