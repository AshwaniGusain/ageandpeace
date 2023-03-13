<?php

namespace Snap\Media\Modules;

use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Form\Inputs\Dependent;
use Snap\Form\Inputs\File;
use Snap\Form\Inputs\Hidden;
use Snap\Form\Inputs\Number;
use Snap\Form\Inputs\Select;
use Snap\Form\Inputs\Text;
use Snap\Media\Models\DefaultMedia;
use Snap\Media\Models\Media;
use Snap\Admin\Modules\Services\FormService;
use Snap\Taxonomy\Models\Taxonomy as TaxonomyModel;
use Snap\Taxonomy\Ui\Taxonomy;

class MediaModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\GridTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    //use \Snap\Admin\Modules\Traits\FiltersTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\AjaxTrait;

    // use \Snap\Admin\Modules\Traits\LogTrait;
    // use \Snap\Admin\Modules\Traits\RestorableTrait;
    //use \Snap\Admin\Modules\Traits\DeletableTrait;
    // use \Snap\Admin\Modules\Traits\ExportableTrait;

    protected $parent = null;
    protected $handle = 'media';
    protected $name = 'Media';
    protected $pluralName = 'Media';
    protected $menuParent = 'site';
    protected $menuLabel = 'Media';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-image';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    //protected $permissions = [];
    protected $config = null;
    protected $routes = [];
    protected $controller = \Snap\Media\Http\Controllers\MediaController::class;
    protected $model = Media::class;

    public function register()
    {
        $this->addRoute(['get'], 'files.json', '@files', []);
        $this->addRoute(['get'], 'images.json', '@images', []);
        return parent::register();
    }

    public function uiTable($ui, Request $request)
    {
        $data = $this->getQuery()->get();
        $items = [];
        foreach ($data as $i => $d) {
            //$items[$i] = $d;
            $items[$i]['id'] = $d->id;
            $items[$i]['preview'] = (in_array('thumb', $d->getMediaConversionNames()) ? $d->img('thumb', ['style' => 'max-height: 50px;']) : $d->img(['style' => 'max-height: 50px;']));
            $items[$i]['name'] = $d->file_name;
            $items[$i]['collection_name'] = $d->collection_name;
            $items[$i]['disk'] = $d->disk;
            $items[$i]['size'] = $d->human_readable_size;
            $items[$i]['updated_at'] = $d->updated_at;
        }

        $ui->table->setItems($items);
    }

    public function uiEdit($ui, Request $request)
    {
        //$this->form($ui->form, $request);
        //

        //dd($ui->resource->getPath());
        //$relatedResource = $ui->resource->model()->first();

        //$path = $resource->getPath();
        //$path = $relatedResource->getMedia()[0]->getPath();

        $ui->related_panel->setClass('text-center');
        $ui->related_panel->img->setSrc($ui->resource->getUrl())->link->setHref($ui->resource->getUrl());
        $ui->related_panel->list->add(trans('admin::resources.filesize', ['size' => $ui->resource->human_readable_size]));
    }

    /**
     * @param $form
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function form(FormService $form, Request $request)
    {
        /*$form->scaffold();

        $form->addSelect('collection_name', ['equalize_key_value' => true, 'options' => config('snap.media.collections')]);
        $form->addSelect('disk', ['equalize_key_value' => true, 'options' => array_keys(config('filesystems.disks')), 'value' => config('medialibrary.default_filesystem')]);
        $form->addSelect('model_type', ['required' => true, 'options' => config('snap.media.model_types')])->move('model_type', 'before:model_id');

        $form->addFile('file', ['preview' => true, 'attrs' => []])->move('file', 'before:model_type');


        $form->addDependent('meta', ['label' => false, 'url' => $this->url('ajax/meta'), 'source' => 'collection_name'], 'Meta');

        $form['order_column']->setLabel('Precedence');

        //$form->remove(['model_id', 'size', 'manipulations', 'custom_properties', 'responsive_images', 'mime_type']);
        // Won't work because it's a relationship field
        //$form->get('model_type')->setOptions(config('snap.admin.media.model_types'))->setPlaceholder(true);
        //$form->move('model_type', 'before:model_id');
        //$form->addFile('file', ['preview' => true, 'attrs' => ['class' => 'toggle', 'data-toggle-value' => 'App\Models\Post']])->move('file', 'before:model_type');

        //$form->remove(['size', 'mime_type', 'manipulations', 'responsive_images']);
        //$form->addList('custom_properties', []);

        //$form->addTable('table', ['module' => 'post', 'filters' => [], 'attrs' => []]);
        $form->remove(['size', 'mime_type', 'custom_properties', 'manipulations', 'responsive_images']);
        //return $form;*/
    }

    public function inputs(Request $request = null, $resource = null)
    {
        $inputs = [
            File::make('file', []),
            Text::make('file_name'),
            Select::Make('disk', ['equalize_key_value' => true, 'options' => array_keys(config('filesystems.disks')), 'value' => config('medialibrary.default_filesystem')]),
            Number::make('order_column', ['label' => 'Precedence']),
        ];

        if (\Admin::modules()->has('taxonomy')) {
            $inputs[] = Taxonomy::make('tags', ['options' => TaxonomyModel::lists('display_name')]);
        }

        $inputs[] = Select::make('collection_name', ['label' => 'Collection', 'equalize_key_value' => true, 'options' => config('snap.media.collections')]);
        $inputs[] = Dependent::make('meta', ['label' => false, 'url' => $this->url('ajax/meta'), 'source' => 'collection_name'], 'Meta');
        $inputs[] = Hidden::make('model_type', []);
        $inputs[] = Hidden::make('model_id', []);

        return $inputs;
    }

    public function getListingItems()
    {
        $data = array();
        $items = $this->getQuery()->get();
        foreach ($items as $item) {
            $data[$item->id] = $item;
            if ($item->category_id) {
                $data[$item->category->id] = $item->category;
                $data[$item->category->id]['parent_id'] = null;
                $data[$item->id]['parent_id'] = $item->category->id;
            }
        }

        return $data;
    }

    public function ajaxOptions(Request $request)
    {
        return $this->getModel()->where('model_type', $request->input('model_type'))->lists('file_name');
    }

    public function ajaxMeta($request)
    {
        $form = $this->getMetaForm($request);
        if ($form) {
            return $form->render();
        } else {
            return 'No meta fields have been configured for the specified collection of <strong>"'.$request->get('collection_name').'"</strong>.';
        }
    }

    protected function getMetaForm($request)
    {
        return \Media::metaForm($request->input('id'), $request);
    }

    public function beforeSave($resource, Request $request)
    {
        $metaForm = $this->getMetaForm($request);
        if ($metaForm) {
            $fields = $metaForm->inputs();
            if ($fields) {
                if (!$resource->meta()->addFields($fields)->set($request->input('meta'))->save()) {

                }
            }
        }

        if (empty($resource->model_type)) {
            $resource->model_type = DefaultMedia::class;
        }

        if (!$request->input('model_id')) {
            $model = new DefaultMedia();
            $model->save();
            $resource->model_id = $model->getKey();
        }

        if (!$request->input('name')) {
            $resource->name = $request->file('file') ? $request->file('file')->getClientOriginalName() : '';
        }

        if (!$request->input('file_name')) {
            $resource->file_name = $request->file('file') ? $request->file('file')->getClientOriginalName(): '';
        }

        if (!$request->input('size')) {
            $resource->size = $request->file('file') ? $request->file('file')->getSize() : 0;
        }

        if (empty($resource->manipulations)) {
            $resource->manipulations = [];
        }

        if (empty($resource->custom_properties)) {
            $resource->custom_properties = [];
        }

        if (empty($resource->responsive_images)) {
            $resource->responsive_images = [];
        }
    }
}