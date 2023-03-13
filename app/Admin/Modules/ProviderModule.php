<?php

namespace App\Admin\Modules;

use App\Models\Company;
use App\Models\Provider;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FiltersService;
use Snap\Admin\Modules\Services\IndexableService;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Admin\Modules\Traits\Filters\Filter;
use Snap\Form\Inputs\SwitchInput;
use Snap\Support\Helpers\GoogleHelper;
use App\Models\Zip;

class ProviderModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\MapTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    //use \Snap\Admin\Modules\Traits\FiltersTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    use \Snap\Admin\Modules\Traits\IndexableTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    protected $parent = null;

    protected $handle = 'provider';

    protected $name = 'Provider';

    protected $pluralName = 'Providers';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Providers';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-user-md';

    protected $path = __DIR__;

    protected $modules = [
        ProviderTypeModule::class,
        ProviderTypeGroupModule::class,
    ];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = Provider::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->defaultSort('user.name')
            ->columns(['company.name' => 'Company', 'user.name', 'user.email', 'zip', 'active', 'updated_at'])
            ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['user:active'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['company.name', 'user.name', 'user.email', 'zip', 'active'])
        ;
    }

    //public function filters(FiltersService $filters, Request $request)
    //{
    //    $filters
    //        ->add(Filter::make('age', 'whereBetween')->withInput('between', ['label' => 'Age Range', 'value' => []]))
    //    ;
    //}

    public function filtersForm($form)
    {
        $form->addSelect('company_id', [
            'label'       => 'Company',
            'options'     => Company::lists('name'),
            'placeholder' => trans('form::fields.select_one'),
        ])->addSelect('state', [
            'label'       => 'State',
            'options'     => array_keys(config('snap.states')),
            'placeholder' => trans('form::fields.select_one'),
        ]);
    }


    public function form($form, Request $request, $resource)
    {
        $form->scaffold();
        // We'll use the user_id instead of the default id for this form
        $form->get('company_id')->setModule('company');
//        $form->get('category_id')->setModule('category');
        $form->get('membership_type_id')->setModule('membership_type');

        $form->get('provider_type_id')->setModule('provider.provider_type');

        $resource = $request->route('resource');
        $form->get('user_id')
             ->setModule('user')
             ->setOptions(User::role('provider')->withoutGlobalScopes()->withTrashed()->lists('name_email'))
        ;

        //$values = ['name' => '', 'email' => '', 'active' => true];
        //if ($resource && $user = $resource->user) {
        //    $values['name']   = $user->name;
        //    $values['email']  = $user->email;
        //    $values['active'] = $user->isActive();
        //}
        //$values = array_merge($values, (array)old('user'));
        //$form->addText('name', ['order' => 0, 'scope' => 'user[]', 'required' => true, 'value' => $values['name']])
        //     ->addEmail('email', ['order' => .5, 'scope' => 'user[]', 'required' => true, 'value' => $values['email']])
        //     ->addBoolean('active', ['scope' => 'user[]', 'checked' => $values['active'] == 1])
        //     ->addHidden('user_id');

        $form->addWysiwyg('description', [
            'required' => true,
            'options'  => ['buttons' => ['bold', 'italic', 'link', 'line', 'redo', 'undo', 'underline', 'ol', 'ul']]
        ]);

        $form->setDefaultGroup('Info')->addMedia('logo', [
            'label'    => 'Logo',
            'options'  => ['collection' => 'provider-logo'],
            'multiple' => false,
        ], 'Images')->addMedia('image', [
            'label'    => 'Hero',
            'options'  => ['collection' => 'provider-hero'],
            'multiple' => false,
        ], 'Images');

        $form->addCoordinates('geo_point', [
            'label'   => 'Coordinates',
            'comment' => 'If you leave these fields empty, they will automatically be looked up by Google.',
            'value'   => ($resource ? $resource->geo_point : null)
        ], 'Info')->move('geo_point', 'after:zip');


        $zips = ($resource) ? $resource->zipCodes->pluck('zipcode') : [];

        $form->addList('searchable_zips',
            ['label' => 'Searchable Zips (To Appear in All Zip Codes Select National Below)', 'value' => $zips])->move
        ('searchable_zips', 'after:geo_point');

        $form->addBoolean('national', ['checked' => $form->get('national')->getValue() == 1]);
        $form->get('national')->setLabel('National (Appear in All Zip Codes)');

        $form->move('national', 'after:searchable_zips');

    }

    public function getValidationRules($resource, $request)
    {
        $rules                 = parent::getValidationRules($resource, $request);
        $rules['user.name']    = 'required';
        $rules['user.email'][] = 'required';
        $uniqueRule            = Rule::unique('users', 'users.email');
        if (isset($resource->user)) {
            $uniqueRule->ignore($resource->user->id, 'id');
        }
        $rules['user.email'][] = $uniqueRule;

        // We remove this required validation rule so it the ResourceRequest will get to the save method
        // on the controller to save any user specific information.
        unset($rules['user_id']);

        return $rules;
    }

    public function getValidationMessages()
    {
        return [
            'user.name.required'  => 'The name field is required.',
            'user.email.required' => 'The email field is required.',
            'user.email.unique'   => 'The email address is already taken.',
        ];
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request, $resource = null)
    {
        $relatedInfo->moveInputs(['active']);
    }

    protected function indexable(IndexableService $indexable, Request $request)
    {
        $indexable->fields(['name', 'email', 'street', 'zip', 'phone', 'website']);
    }

    public function beforeSave($resource, $request)
    {
        // Not being used yet.
        //if ($request->input('gallery')) {
        //    $gallery = $request->input('gallery');
        //    foreach ($gallery as $key => $val) {
        //        if (isset($val['image_options']) && is_string($val['image_options'])) {
        //            $options                        = json_decode($val['image_options'], true);
        //            $options['custom_properties']   = ['caption' => $val['caption']];
        //            $gallery[$key]['image_options'] = $options;
        //            $request->merge(['gallery' => $gallery]);
        //        }
        //    }
        //}

        if (empty($resource->slug)) {
            $resource->slug = Str::slug($resource->user->name);
        }

        if (empty($request->latitude) || empty($request->longitude)) {
            $coords = GoogleHelper::geoLocate($resource->full_address);
        } else {
            $coords['latitude']  = $request->input('latitude');
            $coords['longitude'] = $request->input('longitude');
        }

        if ($coords['latitude'] != 0 && $coords['longitude'] != 0) {
            $resource->geo_point = new Point($coords['latitude'], $coords['longitude']);
        }
    }

    public function afterSave($resource, $request)
    {
        if (isset($request['searchable_zips'])) {
            $zipModels = Zip::whereIn('zipcode', $request['searchable_zips'])->whereDoesntHave('providers',
                function ($query) use ($resource) {
                    $query->where('provider_id', $resource->id);
                })->get();

            $resource->zipCodes()->attach($zipModels);

            $oldZipModels = $resource->zipCodes->whereNotIn('zipcode', $request['searchable_zips']);

            foreach ($oldZipModels as $oldZipModel) {
                $resource->zipCodes()->detach($oldZipModel);
            }
        }
    }
}
