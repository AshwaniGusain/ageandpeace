<?php

namespace App\Admin\Modules;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FormService;
use Snap\Admin\Modules\Services\TableService;

class TaskModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;

    //use \Snap\Admin\Modules\Traits\DeletableTrait;

    protected $parent = null;

    protected $handle = 'task';

    protected $name = 'Task';

    protected $pluralName = 'Tasks';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Tasks';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-check-circle';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = Task::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->defaultSort('title')
            ->columns(['title', 'description', 'category.name' => 'Category', 'created_at', 'updated_at'])
            ->format('\Snap\DataTable\DataTableFormatters::truncateFormatter,50', ['description'])
        ;
    }


    public function form(FormService $form, Request $request)
    {
        $form->scaffold(['except' => ['customerTasks']]);
        $form->addMedia('file', [
            'options'  => ['collection' => 'task-files'],
            'multiple' => false,
        ]);
        $form->get('category_id')->setModel(Category::class);
        //$options = Category::LowestLevel()->orderBy('name')->get()->pluck('name', 'id');
        //$form->get('category_id')->setModule('category')->setOptions($options);
    }
}