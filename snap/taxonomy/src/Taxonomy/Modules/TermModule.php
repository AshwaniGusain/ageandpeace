<?php

namespace Snap\Taxonomy\Modules;

use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Modules\ResourceModule;
use Snap\Form\Inputs\Checkbox;
use Snap\Form\Inputs\Slug;
use Snap\Form\Inputs\Text;
use Snap\Taxonomy\Models\Term;

class TermModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\AjaxTrait;

    //protected $parent = 'taxonomy';
    protected $handle = 'term';
    protected $name = 'Term';
    protected $pluralName = 'Terms';
    protected $menuParent = 'taxonomy';
    protected $menuLabel = 'Terms';
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
    protected $model = Term::class;

    public function register()
    {
        parent::register();
    }

    public function form($form, Request $request)
    {
        $form->scaffold();
        return $form;
    }

}