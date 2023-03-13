<?php

namespace App\Admin\Modules;

use App\Models\Invite;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;

class InviteModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchableTrait;

    //use \Snap\Admin\Modules\Traits\FilterableTrait;

    // use \Snap\Admin\Modules\Traits\LogTrait;
    // use \Snap\Admin\Modules\Traits\RestorableTrait;
    // use \Snap\Admin\Modules\Traits\DeletableTrait;
    // use \Snap\Admin\Modules\Traits\ExportableTrait;
    // use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    // use \Snap\Admin\Modules\Traits\ViewableTrait;

    protected $parent = null;
    protected $handle = 'invite';
    protected $name = 'Invite';
    protected $pluralName = 'Invites';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Invites';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-envelope';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
	protected $permissions = ['permission:admin actions'];
    protected $config = null;
    protected $routes = [];
    protected $controller = '\App\Admin\Controllers\InviteController';
    protected $model = Invite::class;
    protected $ui = [
        'table'         => 'module.resource.table',
        'listing'       => 'module.resource.listing',
        'create'        => 'module.resource.create',
        'create_inline' => 'module.resource.create_inline',
        'edit'          => 'module.resource.edit',
        'edit_inline'   => 'module.resource.edit_inline',
        'field'         => 'module.resource.field',
    ];

    public function uiTable($ui, Request $request)
    {
//        $query = $this->sortQuery($request->get('__col__'), $request->get('__order__'));

        $ui->table
            ->setItems($this->getQuery()->get())//->setColumns(['name', 'street', 'city', 'state', 'zip'])
        ;

    }

    public function uiForm($ui, Request $request)
    {
        $ui->form
            ->add('role', 'select', ['required' => true, 'placeholder' => trans('form::fields.select_one'),
                                     'options'  => [
                                         'admin'    => 'admin',
                                         'provider' => 'provider',
                                     ],
            ])
            ->exclude(['token'])
            //->move('role', 'before:email')
//            ->order(['role', 'email'])
            //->assign('display_value', true)
        ;
    }

}