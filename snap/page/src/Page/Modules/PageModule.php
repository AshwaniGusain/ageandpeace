<?php

namespace Snap\Page\Modules;

use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Form\Contracts\NestableInterface;
use Snap\Form\Inputs\DateTime;
use Snap\Form\Inputs\Select;
use Snap\Form\Label;
use Snap\Navigation\Models\Navigation;
use Snap\Page\Models\Page;
use Snap\Page\Http\Controllers\PageController;
use Form;

class PageModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\PreviewTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\ListingTrait;
    use \Snap\Admin\Modules\Traits\GridTrait;
    use \Snap\Admin\Modules\Traits\RestorableTrait;
    //use \Snap\Admin\Modules\Traits\FiltersTrait;

    protected $parent = null;
    protected $handle = 'page';
    protected $name = 'Page';
    protected $pluralName = 'Pages';
    protected $menuParent = 'site';
    protected $menuLabel = 'Pages';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-wpforms';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    //protected $permissions = [];
    protected $config = null;
    protected $routes = [];
    protected $controller = PageController::class;
    protected $model = Page::class;

    public function register()
    {
        parent::register();
        $this->addRoute(['get','post'], 'template/{template}/{resource?}', '@template', ['as' => 'template']);
        $this->addRoute(['get'], '{resource}/thumb/{regenerate?}', '@makeThumb', ['as' => 'thumb']);
        $this->addRoute(['get'], 'urls.json', '@urls', ['as' => 'urls']);
    }

    public function table($table, Request $request)
    {
        $table
            ->format(function($val, $data){
                return '<snap-thumb url="'.$data['thumb'].'"></snap-thumb>';
                //  return '<img src="'.$data['thumb'].'" alt="'.$data['uri'].'" style="max-height: 50px; max-width: 200px;">';
            }, ['thumbnail'])
            ->format('\Snap\DataTable\DataTableFormatters::widthFormatter,120', ['name', 'uri'])
            ->format('\Snap\DataTable\DataTableFormatters::containerFormatter,100,200', ['thumbnail'])
            ->format('\Snap\DataTable\DataTableFormatters::naFormatter', ['publish_date'])
        ;

        $table->columns([
            'name',
            'uri' => trans('page::messages.uri'),
            'type',
            'thumbnail',
            //'publish_date',
            'status',
            'created_at',
            'updated_at',
        ]);

        $table->nonSortable(['thumbnail']);

        // Remove the thumbnail column if it's not enable in the config.
        if (!config('snap.pages.thumbnail.enabled')) {

            // Index 2 is the thumbnail column's index.
            unset($table->columns[2]);
        }
    }

    public function uiEdit($ui, Request $request)
    {
        $ui->preview->setSlugInput('uri');
        if ($ui->resource->isDraft()) {
            $ui->alerts->addWarning(trans('page::messages.warning_draft'));
        }
    }

    protected function form($form, Request $request, $resource = null)
    {
        $form
            ->addText('uri', [
                    'label' => trans('page::messages.uri'),
                    'required' => true,
                    'placeholder' => trans('page::messages.uri_placeholder'),
                    'comment' => trans('page::messages.name_comment'),
                ]
            )
            ->addMirror('name', [
                    //'required' => true,
                    'bound_to' => 'uri',
                    'comment' => trans('page::messages.name_comment')]
            )
            ->addTemplate('type', [
                'templates' => 'Pages',
                'meta_scope' => 'meta',
                'required' => true,
                'resource' => $request->resource,
                'comment' => trans('page::messages.type_comment'),
            ])
        ;

        // Need to reflash because this is being ajaxed in.
        // need a better solution for validating template meta data.
        request()->session()->reflash();

        // Modify label to say "Location" instead of "URI"
        $form->validationMessage('uri.unique', trans('page::messages.error_uri_taken'));

        // This allows us to set validation rules on the meta page variables in the template.

        //$form->validationValues(array_merge($request->all(), (array) $request->get('meta')));

        if ($resource) {
            $template = \Template::get($resource->type);
            if ($template) {
                $f = $template->getForm();
                $this->assignTemplateValidationRules($form, $f->inputs());
            }
            //foreach ($f->inputs() as $input) {
            //    $form->rule('meta.'.$input->key, $input->getRules(), $input->getValidationMessages(), $input->getValidationAttribute());
            //}
        }
    }

    //public function validationValues($request, $resource = null)
    //{
    //    return array_merge($request->all(), (array) $request->get('meta'));
    //}

    protected function assignTemplateValidationRules($form, $inputs, $prefix = 'meta.')
    {
        foreach ($inputs as $input) {
            $form->rule($prefix.$input->key, $input->getRules(), $input->getValidationMessages(), $input->getValidationAttribute());
            if ($input instanceof NestableInterface) {
                $this->assignTemplateValidationRules($form, $input->getInputs(), $prefix.$input->key.'.*.');
            }
        }
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request)
    {
        $resource =$relatedInfo->resource;

        // Create thumbnail if configured to display.
        if ($resource && Page::hasThumbnails()) {
            $urlParams = ['resource' => $resource->id];
            if (session()->has('success')) {
                $urlParams['generate'] = 1;
            }
            $thumbUrl = $this->url('thumb', $urlParams);
            $relatedInfo->add('<snap-thumb url="'.$thumbUrl.'"></snap-thumb>');
        }

        // Create related navigation item if navigation module is being used.
        if ($resource && \Admin::modules()->has('navigation')) {
            $navModule = \Admin::modules()->get('navigation');
            $nav = $navModule->getModel()->where('url', '=', $resource->uri)->first();
            $relatedNavLabel = Label::make(trans('page::messages.related_navigation'))->render();
            if ($nav) {
                $relatedInfo->add(
                    $relatedNavLabel.
                    '<div><a href="'.$navModule->url('edit', ['resource' => $nav->id]).'"><i class="'.$navModule->icon().'"></i> '.$nav->label.'</a></div>');
            } elseif ($resource) {
                $parent = $resource->parent;
                $parentNavId = ($parent) ? Navigation::where('url', '=', $parent->uri)->first()->id : '';
                $createParams = "label=".$resource->name."&url=".$resource->uri."&key=".$resource->uri."&parent_id=".$parentNavId;

                $relatedInfo->add(
                    $relatedNavLabel.
                    '<div><a href="'.$navModule->url('create').'?'.$createParams.'"><i class="'.$navModule->icon().'"></i> '.trans('admin::resources.create').'</a></div>');
            }
        }

        // Provide status & publish date selection dropdown.
        $relatedInfo
            ->add(
                Select::make('status', ['id' => 'status', 'value' => $resource->status ?? null, 'options' => [
                    Page::STATUS_PUBLISHED   => trans('page::messages.status_published'),
                    Page::STATUS_UNPUBLISHED => trans('page::messages.status_unpublished'),
                    Page::STATUS_DRAFT       => trans('page::messages.status_draft')]])->render()
            )
            ->add(
                DateTime::make('publish_date', ['id' => 'publish_date', 'value' => $resource->publish_date ?? null ])->render()
            )
        ;
    }

    public function beforeSave($resource, $request)
    {
        //$template = \Template::get($resource->type);
        //
        //$form = $template->getForm();
        //
        //$metaScope = request()->input('scope', 'meta');
        //$fields = $this->getVocabularyMetaForm($request)->inputs();
        //if ($fields) {
        //    $resource->meta()->addFields($fields)->set($request->input('meta'))->save();
        //}
    }
}