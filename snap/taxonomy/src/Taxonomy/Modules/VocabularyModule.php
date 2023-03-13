<?php

namespace Snap\Taxonomy\Modules;

use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\TableService;
use Snap\Form\Inputs\Checkbox;
use Snap\Form\Inputs\Number;
use Snap\Form\Inputs\Slug;
use Snap\Form\Inputs\Table;
use Snap\Form\Inputs\Text;
use Snap\Taxonomy\Models\Taxonomy;
use Snap\Taxonomy\Models\Vocabulary;

class VocabularyModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    //use \Snap\Admin\Modules\Traits\FiltersTrait;

    //protected $parent = 'taxonomy';
    protected $handle = 'vocabulary';
    protected $name = 'Vocabulary';
    protected $pluralName = 'Vocabularies';
    protected $menuParent = 'taxonomy';
    protected $menuLabel = 'Vocabularies';
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
    protected $model = Vocabulary::class;


    public function table(TableService $table, Request $request)
    {
        $table->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active']);
    }

    public function form($form, Request $request)
    {
        // Using inputs as alternative below
    }

    public function inputs(Request $request = null, $resource = null)
    {
        return [
            Text::make('name', ['required' => true]),
            Slug::make('handle', ['bound_to' => 'name', 'required' => true]),
            Number::make('max_depth', ['max' => 9]),
            Checkbox::make('active', ['checked' => true]),
            Table::make('terms')->setModule('taxonomy')->setFilters(($resource ? ['vocabulary_id' => $resource->id] : null))->visible(!empty($resource)),
        ];
    }

}