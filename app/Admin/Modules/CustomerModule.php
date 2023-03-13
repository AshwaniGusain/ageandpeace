<?php

namespace App\Admin\Modules;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Snap\Admin\Modules\ResourceModule;
use Illuminate\Support\Facades\Log;
use Snap\Admin\Modules\Services\FiltersService;
use Snap\Admin\Modules\Services\FormService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Admin\Modules\Traits\Filters\Filter;
use Snap\Support\Helpers\GoogleHelper;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class CustomerModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\FiltersTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    protected $parent = null;

    protected $handle = 'customer';

    protected $name = 'Customer';

    protected $pluralName = 'Customers';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Customers';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-users';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $model = Customer::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->defaultSort('user.name')
            ->columns(['user.name', 'user.email', 'zip', 'age', 'active', 'updated_at'])
            ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['user:active'])
        ;
    }

    public function filters(FiltersService $filters, Request $request)
    {
        $filters->add(Filter::make('age', 'whereBetween')->withInput('between', ['label' => 'Age Range', 'value' => []]))
        ;
    }

    public function form(FormService $form, Request $request)
    {
        $form->scaffold();

        // We'll use the user_id instead of the default id for this form
        $resource = $request->route('resource');

        $values = ['name' => '', 'email' => '', 'active' => true];
        if ($resource && $user = $resource->user) {
            $values['name'] = $user->name;
            $values['email'] = $user->email;
            $values['active'] = $user->isActive();
        }
        $values = array_merge($values, (array) old('user'));
        $form->addText('name', ['order' => 0, 'scope' => 'user[]', 'required' => true, 'value' => $values['name']])
             ->addText('zip', ['required' => true])
             ->addNumber('age', [])
             ->addEmail('email', ['order' => 0, 'scope' => 'user[]', 'required' => true, 'value' => $values['email']])
             ->addBoolean('active', ['scope' => 'user[]', 'checked' => $values['active'] == 1])
             ->addHidden('user_id')
        ;

        // Set validation rules
        //$rules['user.name'] = 'required';
        //$rules['user.email'][] = 'required';
        //
        //$uniqueRule = Rule::unique('users', 'users.email');
        //if (isset($resource->user)) {
        //    $uniqueRule->ignore($resource->user->id, 'id');
        //}
        //$rules['user.email'][] = $uniqueRule;
        //
        //// We remove this required validation rule so it the ResourceRequest will get to the save method
        //// on the controller to save any user specific information.
        //unset($rules['user_id']);

        //$form->rules($rules);

    }

    public function getValidationRules($resource, $request)
    {
        $rules = parent::getValidationRules($resource, $request);
        $rules['user.name'] = 'required';
        $rules['user.email'][] = 'required';

        $uniqueRule = Rule::unique('users', 'users.email');
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

    public function beforeSave($resource, $request)
    {
        $coords = GoogleHelper::geoLocate($resource->zip);

        if ($coords['latitude'] != 0 && $coords['longitude'] != 0) {
            $resource->geo_point = new Point($coords['latitude'], $coords['longitude']);
        }
    }

}