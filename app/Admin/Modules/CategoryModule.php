<?php

namespace App\Admin\Modules;

use App\Models\Category;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\IndexableService;

class CategoryModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\ListingTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    use \Snap\Admin\Modules\Traits\IndexableTrait;

    //use \Snap\Admin\Modules\Traits\FilterableTrait;

    // use \Snap\Admin\Modules\Traits\LogTrait;
    // use \Snap\Admin\Modules\Traits\RestorableTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;

    // use \Snap\Admin\Modules\Traits\ExportableTrait;
    // use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    // use \Snap\Admin\Modules\Traits\ViewableTrait;

    protected $parent = null;

    protected $handle = 'category';

    protected $name = 'Category';

    protected $pluralName = 'Categories';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Categories';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-share-alt';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = Category::class;

    public function uiTable($ui, Request $request)
    {
        $this->getQuery()->leftJoin('categories AS c', 'c.id', '=', 'categories.parent_id')
             ->select('categories.id', 'categories.name', 'categories.slug', 'c.name AS parent', \DB::raw('IF(categories.active = 1, "yes", "no") as active'), 'categories.updated_at')
        ;
    }

    public function form($form, Request $request, $resource)
    {
        $form->scaffold(['props' => ['description' => ['type' => 'wysiwyg']]]);
        $form->get('slug')->setBoundTo('#name');
        //$form->get('description')->setAutoSize(true);
        if ($resource) {
            $form->get('parent_id')->getOptions()->forget($resource->id);
        }

        return $form;
    }

    protected function indexable(IndexableService $indexable, Request $request)
    {
        $indexable->fields(['name', 'description']);
    }

}