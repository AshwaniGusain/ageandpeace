## Snap Admin ##
The SNAP Admin package simplifies creating complicated administrative backends in Laravel.

#### Admin Service Provider ####
This `AdminServiceProvider` bootstraps provides the following:

* Binds a singleton `admin` object to the service container
* Provides an `Admin` facade
* Creates an `admin::` view namespace for view files located in the admin package
* Loads routes found in `app/Admin/routes.php`

#### Admin Helpers ####
* `admin_path($path = '')`: Returns the server path to the admin
* `admin_url($path = '')`: Returns an absolute URL to the admin 
* `admin_uri($path = '')`: Returns a URI path to the admin

#### Admin Config ####
The config is located at `config/snap/admin.php`. The following are the the different options:

* `title`: This is what is displayed in the left above the menu and is used in the HTML's title tag.
* `route`:
	* `prefix`
	* `middelware`
	* `namespace`
* `path`:
* `modules`:
	* `dashboard`
	* `media`
	* `user`
* `ui`:
* `media`:
* `menu`:
* `date_format`:


```
'title' => 'Age & Peace',

    'route' => [
        'prefix'     => 'admin',
        'middleware' => ['web'],
        'namespace'  => 'App\Admin',
    ],

    'path' => app_path('Admin'),

    'modules' => [
        'dashboard'       => \Snap\Admin\Modules\DashboardModule::class,
        'user'            => [
            'class'  => \App\Admin\Modules\UserModule::class,
            'config' => [],
        ],
        'company'         => \App\Admin\Modules\CompanyModule::class,
        'provider'        => \App\Admin\Modules\ProviderModule::class,
        'customer'        => \App\Admin\Modules\CustomerModule::class,
        'invite'          => \App\Admin\Modules\InviteModule::class,
        'membership_type' => \App\Admin\Modules\MembershipTypeModule::class,
        'post'            => \App\Admin\Modules\PostModule::class,
        'task'            => \App\Admin\Modules\TaskModule::class,
        'category'        => \App\Admin\Modules\CategoryModule::class,
        'media'           => \Snap\Admin\Modules\MediaModule::class,
    ],

    'ui' => [
        'layout'                        => Snap\Admin\Ui\Layout::class,
        'login'                         => Snap\Admin\Ui\Auth\Login::class,
        'module.dashboard'              => Snap\Admin\Ui\Module\DashboardPage::class,
        'module.resource.index'         => Snap\Admin\Ui\Module\Resource\IndexPage::class,
        'module.resource.table'         => Snap\Admin\Ui\Module\Resource\TablePage::class,
        'module.resource.listing'       => Snap\Admin\Ui\Module\Resource\ListingPage::class,
        'module.resource.create'        => Snap\Admin\Ui\Module\Resource\CreatePage::class,
        'module.resource.create_inline' => Snap\Admin\Ui\Module\Resource\CreateInlinePage::class,
        'module.resource.edit'          => Snap\Admin\Ui\Module\Resource\EditPage::class,
        'module.resource.edit_inline'   => Snap\Admin\Ui\Module\Resource\EditInlinePage::class,
        'module.resource.input'         => Snap\Admin\Ui\Module\Resource\InputPage::class,
        'module.resource.delete'        => Snap\Admin\Ui\Module\Resource\DeletePage::class,

    ],

    'media' => [
        'collections' => ['default', 'images'],

    ],

    'menu' => [
        'admin' => ['label' => 'Admin', 'parent' => null, 'link' => false],
        //        'admin' => ['label' => 'Admin', 'parent' => null, 'link' => false],
    ],

    'date_format' => 'm/d/Y',
```