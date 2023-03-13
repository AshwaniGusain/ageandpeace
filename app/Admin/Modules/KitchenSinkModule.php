<?php

namespace App\Admin\Modules;

use App\Models\KitchenSink;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\ExportableService;
use Snap\Admin\Modules\Services\GridService;
use Snap\Admin\Modules\Services\IndexableService;
use Snap\Admin\Modules\Services\LogService;
use Snap\Page\Modules\Services\PublicRoutesService;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Admin\Modules\Traits\Filters\Filter;

class  KitchenSinkModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\IndexableTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    use \Snap\Admin\Modules\Traits\RestorableTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Page\Modules\Traits\PublicRoutesTrait;
    //use \Snap\Admin\Modules\Traits\PreviewTrait;
    //use \Snap\Website\Modules\Traits\ResourcePagesTrait;

    use \Snap\Admin\Modules\Traits\GridTrait;
    use \Snap\Admin\Modules\Traits\ListingTrait;
    use \Snap\Admin\Modules\Traits\CalendarTrait;
    use \Snap\Admin\Modules\Traits\MapTrait;
    use \Snap\Admin\Modules\Traits\FiltersTrait;
    use \Snap\Admin\Modules\Traits\LogTrait;
    use \Snap\Admin\Modules\Traits\ExportableTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\ShowTrait;
    use \Snap\Admin\Modules\Traits\DuplicateTrait;
    use \Snap\Admin\Modules\Traits\AjaxTrait;

    protected $parent = null;

    protected $handle = 'kitchensink';

    protected $name = 'Kitchen Sink';

    protected $pluralName = 'Kitchen Sink';

    protected $menuParent = 'modules';

    protected $menuLabel = 'Kitchen Sink';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-shower';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    //protected $permissions = ['permission:admin actions'];
    //protected $permissions = [];

    protected $config = null;

    protected $routes = [];

    protected $controller = ResourceModuleController::class;

    protected $defaultRouteMethod = 'index';

    protected $model = KitchenSink::class;

    protected $migration = 'CreateKitchenSinkTable';

    protected $previewUriPrefix = '/brands/';

    protected $scaffoldForm = false;

    protected $scaffoldScopes = ['onlyTrashed'];

    protected $publicBaseUri = 'kitchensink';

    /*protected $ui = [
        'table'            => 'module.resource.table',
        'listing'          => 'module.resource.listing',
        'map'              => 'module.resource.map',
        'grid'             => 'module.resource.grid',
        'calendar'         => 'module.resource.calendar',
        'create'           => 'module.resource.create',
        'create_inline'    => 'module.resource.create_inline',
        'edit'             => 'module.resource.edit',
        'edit_inline'      => 'module.resource.edit_inline',
        'delete'           => 'module.resource.delete',
        'delete_inline'    => 'module.resource.delete_inline',
        'duplicate'        => 'module.resource.duplicate',
        'duplicate_inline' => 'module.resource.duplicate_inline',
        'field'            => 'module.resource.field',
        'compare'          => 'module.resource.compare',
    ];*/

    public function publicRoutes()
    {
        $this->service('publicRoutes')
            ->urls([
                url('kitchensink'),
                function($model){
                return 'xxx';
            }])
             //->prefix('kitchensink')
             ->route('\App\Http\Controllers\PublicKitchenSinkController')
            ;
        //$this->router->resourcePages('kitchensink', '\App\Http\Controllers\PublicKitchenSinkController');
        //$publicRoutes->prefix('kitchensink')
        //             ->route('\App\Http\Controllers\PublicKitchenSinkController')
        //;
    }

    public function publicRoutesService(PublicRoutesService $publicRoutes, Request $request)
    {
        $publicRoutes
            ->prefix('kitchensink')
            //->urls(function($model){
            //    return 'zzz';
            //})
        ;
        //$this->router->resourcePages('kitchensink', '\App\Http\Controllers\PublicKitchenSinkController');
        //$publicRoutes->prefix('kitchensink')
        //             ->route('\App\Http\Controllers\PublicKitchenSinkController')
        //;
    }

    public function filters($filters, Request $request)
    {
        $filters->add(Filter::make('name', 'where', '=')->withInput('text'));
    }

    public function scopes($scopes, Request $request)
    {
        //$scopes->scaffold()
        ;
    }

    public function preview($preview)
    {
        //$preview->prefix('/xxx');
    }

    public function indexable(IndexableService $indexable, Request $request)
    {
        $indexable
            ->fields('name', 'slug', 'wysiwyg')
            ->excerpt(function($resource){
                return $resource->wysiwyg;
            });
    }

    public function pages(PagesService $pages, Request $request)
    {
        $pages->types(['archive']);
        //$pages->listing();
    }

    public function log(LogService $log, Request $request)
    {
        $log
            ->enable(true)
            ->message(function($resource){
                return 'The resource '.$resource->display_name.' successfully saved';
            })
            ->level('info')
            ;
    }

    public function grid(GridService $grid, Request $request)
    {
        $grid->cols(4);
    }

    public function export(ExportableService $export, Request $request)
    {
        $export
            //->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
        ->fileName('download.csv')
        ->columns('name', 'active')
        ->format(function($val, $data){
            return ($val) == 1 ? 'Yes' : 'No';
        }, ['active']);
    }

    public function table($table, Request $request)
    {
        //$table->addFormatter('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active']);
        $table
            ->defaultSort('-slug')
            //->sortable(['name'])
            ->columns(['name', 'slug', 'checkbox', 'textarea', 'wysiwyg'])
            //->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
            ->query(function($query){
                //$query->where('name', 'Argyle');
            })->format(function($val, $data){
                return ($val) == 1 ? 'Yes' : 'No';
            }, ['active'])
            ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['checkbox'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format,kitchensink', ['name', 'slug', 'checkbox', 'textarea', 'wysiwyg'])

        ;

    }

    public function uiForm($ui, Request $request)
    {
        if ($ui->resource && !$ui->resource->isActive()) {
            $ui->alerts->add('This resource is inactive.', 'warning');
        }
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request)
    {

    }

    public function form($form, Request $request = null, $resource = null)
    {
        $form->scaffold(['only' => ['name', 'slug', 'parent_id','slug'], 'props' => ['checkbox' => ['type' => 'boolean'], 'image' => ['type' => 'media']]]);
        //$form->scaffold(['only' => ['name']]);
        $form->get('name')->setDisplayOnly(true);
        $form->addMultiselect('dualmultiselect2', ['value' => [2, 3], 'options' => [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', '6' => 'six', 'pickupstix' => 'Pick up Sticks']]);
        $form->addFile('images', ['options' => ['collection' => 'images'], 'multiple' => false, 'preview' => true]);
        $form->addState('hobbies', ['placeholder' => 'Select a state...']);
        $form->addMultiRadio('hobbies', ['options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']);
        $form->addSelect('hobbies2', ['placeholder' => 'Select a hobby...', 'options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']);
        $form->addRange('range', ['step' => 5, 'min' => 0, 'max' => 50, 'prefix' => '$']);
        $form->addBoolean('boolean');
        $form->addWysiwyg('wysiwyg', []);
        $form->addUrl('url', ['required' => true, 'validation_messages' => ['required' => 'TEST']]);
        $form->addTaxonomy('tags', []);
        $form->addMedia('image');
        $form->addTextarea('textarea', ['autosize' => true]);
        $form->addBelongsTo('parent_id', ['module' => 'kitchensink', 'model' => KitchenSink::class, 'resource' => $resource]);
        $form->addWysiwyg('wysiwyg', ['class' => 'toggle', 'attrs' => ['data-toggle-value' => '1']]);
        $form->addKeyValue('keyvalue', []);
        $form->addToggle('toggle', ['source' => 'model_type', 'options' => [1 => 'one', 2 => 'two', 3 => 'three'], 'placeholder' => trans('form::inputs.select_one')]);
        $form->addDependent('dependent', ['source' => 'toggle', 'url' => $this->url('ajax', ['method' => 'options']), 'placeholder' => true]);
//dd(json_encode($resource));z
        //$category = $resource->tags()->get();
        //debug_query();
        //dump($resource->tags[0]->vocabulary);
        //dd($resource->tags[0]->name);
        //$form->scaffold();
        //$form->scaffold(['name', 'slug', 'parent_id']);
        //@TODO... scaffold retains saved values on error
        //$form->addInput('name', ['required' => true]);
        //$form->addSlug('slug');
        //$form->addSelect('parent_id');
        //$form->addTextarea('textarea');
        //$form->addMedia('image');
        //$form->addMultiRadio('radios', ['options' => ['one', 'two', 'three']]);
       //$form->addBelongsTo('parent_id', ['module' => 'kitchensink', 'model' => KitchenSink::class, 'resource' => $resource]);
        //$form->addWysiwyg('wysiwyg', ['class' => 'toggle', 'attrs' => ['data-toggle-value' => '1']]);
        //$form->addKeyValue('keyvalue', []);
        //$form->addToggle('toggle', ['source' => 'model_type', 'options' => [1 => 'one', 2 => 'two', 3 => 'three'], 'placeholder' => trans('form::inputs.select_one')]);
        //$form->addDependent('dependent', ['source' => 'toggle', 'url' => $this->url('ajax', ['method' => 'options']), 'placeholder' => true]);
        //
        //$form->addFile('file', ['preview' => true, 'attrs' => ['class' => 'toggle', 'data-toggle-value' => 'App\Models\Post']])->move('file', 'before:model_type');
        //$form->addFile('file', ['preview' => true, 'attrs' => []])->move('file', 'before:model_type');
        //$form->remove(['size', 'mime_type', 'manipulations', 'responsive_images']);
        //$form->addList('custom_properties', []);
        //
        //$form->addTable('table', ['module' => 'kitchensink', 'filters' => [], 'attrs' => []]);
        //$form->remove(['size', 'mime_type', 'custom_properties', 'manipulations', 'responsive_images']);
        //$form->inputs(
        //    [
        //        Slug::make('slug')
        //    ]
        //
        //);
        //$form->get('description')->setRequired();
        //$form->addKeyValue('keyvalue', []);
        //$form->addRepeatable('repeatable', ['inputs' => [
        //    'title' => ['type' => 'text'],
        //    'nested' => ['type' => 'repeatable', 'inputs' => [
        //        //'subtitle' => ['type' => 'text', 'maxlength' => '50'],
        //        'currency' => ['type' => 'currency'],
        //    ]],
        //    //'user_id' => ['type' => 'select', 'module' => 'user'],
        //]]);

        //$form->get($this->getPreviewSlugInput())->setPrefix($this->getPreviewUriPrefix());
    }


    public function inputs()
    {
        return [
            //Wysiwyg::make('wysiwyg'),
            //Select2::make('select2'),
            //Repeatable::make('repeatable', [
            //    'inputs' => [
            //        'user_id' => ['type' => 'select', 'module' => 'user'],
            //    ]
            //]),
                //Slug::make('slug')->displayAs(function($value, $input){
                //  return 'This is the value: '.$value;
                //})
            ];
    }
    //public function getPublicRouteCallback()
    //{
    //    return '\App\Http\Controllers\BrandsController@detail';
    //}


    /**
     * Method that executes before saving the resource.
     *
     * @param $resource
     * @param Request $request
     * @return \Snap\Database\Model\Model
     */
    public function beforeSave($resource, $request)
    {
        return $resource;
    }

    /**
     * Method that executes after saving the resource.
     *
     * @param $resource
     * @param Request $request
     * @return \Snap\Database\Model\Model
     */
    public function afterSave($resource, $request)
    {
        return $resource;
    }

}