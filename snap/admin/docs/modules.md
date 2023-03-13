# Modules #
* [Modules Overview](#overview)
* [Module Generation](#generation)
* [Module Configuration](#config)
* [Admin Module Manager](#manager)
* [Basic Module Properties and Methods](#basic-modules)
* [Resource Modules](#resource-modules)
* [Module Controllers](#controllers)
* [Module Traits]({docs_url}/admin/modules/traits)
* [Module UI]({docs_url}/admin/modules/ui)

## <a id="overview"></a>Modules Overview ##
Modules are the main mechanism used in expanding the admin's functionality.
All modules inherit from the base `\Snap\Admin\Modules\Module` or the **[resource specific](#resource-modules)** `\Snap\Admin\Modules\ResourceModule` class and come
with properties and methods that need to be overwritten to customize your module.
You can think of modules as the glue between your routes, models and views 
and the main place for modifying **[module trait]({docs_url}/admin/module-traits)** properties and methods. 

Below is an example of a simple dashboard module. Note that it uses the [NavigableTrait]({docs_url}/admin/module-traits#NavigableTrait)
to add a menu item in the admin and the use of the magic method of `uiDashboard` to modify the dashboard's view:

```
<?php

namespace Snap\Admin\Modules;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Models\Media;
use Snap\DataTable\DataTable;
use Snap\Ui\Components\Bootstrap\Card;
use Snap\Ui\Components\Bootstrap\ListGroup;

class DashboardModule extends Module
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;

    protected $parent = null;
    protected $handle = 'dashboard';
    protected $name = 'Dashboard';
    protected $pluralName = 'Dashboard';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Dashboard';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-dashboard';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    protected $permissions = ['permission:admin actions'];
    protected $config = null;
    protected $routes = [];
    protected $controller = '\Snap\Admin\Http\Controllers\DashboardController';
    protected $ui = [
        'dashboard'     => 'module.dashboard',
    ];

    public function uiDashboard($ui, Request $request)
    {
        $card = new Card();
        $card->setHeader('Stats')
            ->setBody('This is the body of the card')
            ->setFooter('This is the footer of the card')
        ;
        $ui->grid
            ->add($card)
            ->add($card)
        ;

        $table = new DataTable();
        $data[] = [
            'visits' => 100,
            'time spent' => '1mn',
        ];
        $data[] = [
            'visits' => 200,
            'time spent' => '2mn',
        ];
        $table->load($data);
        $ui->grid->add($table);

        $list = new ListGroup();
        $items = new Collection();
        $items[] = '<a href="#">Post Name 1</a>';
        $items[] = '<a href="#">Post Name 2</a>';
        $list->add($items);

        $ui->grid->add($list);
    }
}
```
## <a id="generation"></a>Module Generation ##
Module's can consist of a single Module class file and can include models ([Resource Modules](#resource-modules)), controllers and migration files.
To help with the creation of these files, there is a `>php artisan module:make {name}` command that can be run with the following options:
```
--resource: Will create a resource module (which will need a model) instead of a normal module (which does not).
--migration: Will create a migration file for the model associated with the module.
--model: Will create an associated model with the module (for resource modules).
--controller: Will create an associated controller for the module.
--path=: The location where the module file should be created.
--all: A shortcut to create a model and a controller both.
--force: Replaces a file if it already exists.
```
*The module's name should match the name of the model name if a resource module and is generally singular.*

### <a id="config"></a>Module Configuration ###
Modules are added to the admin in the `app/Admin/Providers/ModulesServiceProvider.php`:

```
 protected $modules' => [
	'dashboard'       => \Snap\Admin\Modules\DashboardModule::class,
	'user'            => [
    		'class'  => \App\Admin\Modules\UserModule::class,
    		'config' => [],
	],
]
```
Note that you can either specify a class or an array with the keys of `class` and `config`.
Specifying it as an array allows to pass a configuration array to the module which can be accessed
on using the module's `config()` method (e.g. `$my_config_item = $dashboard->config('my_config_item')`). 

---

### <a id="manager"></a>Admin Module Manager ###
To access the module objects you can use the Admin's module manager object:
```
// The dashboard module object.
$dashboard = Admin::modules('dashboard');

// All module objects registered.
$allModules = Admin::modules()->all();

// The current module being viewed in the admin (if available).
$currentModule = Admin::modules()->current();

```

---

### <a id="basic-modules"></a>Basic Module Properties and Methods ###
All modules will have the following basic properties and methods in which you can overwrite
to further customize your module.

##### Basic Module Properties #####
The following are the properties of a generic module you can configure:

* `$parent`: The parent module. For example, a `Blog` module might be a parent module to a `Post` and `Comment` resources module. Default value is `null`.
* `$handle`: A string value to reference the module and is the array key defined in the [admin config](#config). It's usually a lowercase value of the name of the module without spaces.
* `$name`: The friendly name of the module.
* `$pluralName`: The plural version of the module's name.
* `$description`: A description of the module.
* `$version`: The version of the module. Default value is `1.0.0`.
* `$config`: A key => value array of initial configuration values that are accessed with the `$module->config('my_key')`.
* `$icon`: Default value is `fa fa-gear`.
* `$path`: Default value is the current directory (`__DIR__`).
* `$modules`: Sub modules to associate with the module. For example, a `Blog` module might have sub modules of `Post` and `Comment` resources module. 
* `$uri`: The URI path to access the module in the admin (e.g. `admin/user`).
* `$permissions`: An array of permissions associated with the routes of the module.
* `$routes`: An array of routes. To be associated with a module (it's recommended to use the `addRoute()` method instead).
* `$defaultRouteMethod`: The module's default route method to map to. Default value is `index`.
* `$controller`: Default values is `ModuleController::class`.
* `$ui`: A key => value array of mappings to UI classes. The ui classes can be referenced using the modules [ui()]() method.

##### Basic Module Methods #####
* `register()`: The register method is called on instantiation of the module and is used to configure the module early in the process such as routes and trait aliases.
Traits that have a method name of `register{TraitName}` will be called in this method.
* `boot()`: The boot method is called explicitly in the controller and is used to initialize default values for the trait. 
Similar to the `register` method, Traits that have a method name of `boot{TraitName}` will be called in this method.
* `handle()`: Returns the `handle` property by default if the property is provided. Otherwise, it will look at the class name and generate one automatically.
* `parentHandle()`: Returns the parent modules `handle` property if a parent module is associated with the module.
* `fullHandle()`: Returns the full handle path to the module by looking appending the parent module's handle.
Because module's implement the `ArrayAccess` interface, this method provides the full array dot syntax path to the module.
* `name()`: Returns the `name` property by default if the property is provided. Otherwise, it will return a `ucwords` on the `handle()` methods returned value (e.g. user = User).
* `pluralName()`: Returns the `pluralName` property by default if the property is provided. Otherwise, it will return a `str_plural` on the `name()` methods returned value (e.g. User = Users). 
* `description()`: Returns the description property.
* `parent()`: Returns the parent module if it exists.
* `icon()`: Returns the icon property by default.
* `url($uri = '', $params = [], $qs = false)`: 
* `uri($uri = '', $params = [])`: Returns the admin URI for the module relative to the admin's URI path.
* `fullUri($uri = '', $params = [])`: Returns the full admin URI for the module.
* `currentUri()`: Returns the admin module's current URI path (if currently on a module's page in the admin).
* `currentFullUri()`: Returns the admin module's full current URI path (if currently on a module's page in the admin).
* `routeKey($uri)`: Creates the key value to be used for a routes name.
* `addRoute($verbs, $uri, $callback, $options = [])`: Adds to the module's `$route` property array routing information to be executed in the module's `route()` method.
* `routes()`: Creates the routes specified by the `addRoute` method using `Route::match(...)`. Called by Admin::routes().
* `getNamespace()`: Returns the module class's namespace.
* `config($key = null, $default = null)`: Convenience alias to `getConfig()`.
* `getConfig($key = null, $default = null)`: Returns a configuration value with the option to specify a default if the config value doesn't exist.
* `setConfig($key, $val = null)`: Sets a config value on the module.
* `path($file = null)`: Returns the server path to the module class.
* `modules()`: Returns an array of any sub modules that may be associated with the module. 
* `module($handle)`: Returns a single sub module based on the handle passed to the method. 
* `isCurrent()`: Returns a boolean value based on if the currently viewed module is this module.
* `ui($handle, $params = [], $callback = null)`: Returns a UI object for rendering.
* `permissions()`: Returns the permissions property by default.
* `hasPermission($permission)`: @TODO - Returns a boolean based on if the currently logged in user has permission to view the module.
* `version()`: Returns the version property.
* `install()`: @TODO - Module specific installation code.
* `uninstall()`: @TODO - Module specific uninstallation code.

--- 
 
### Resource Modules ###
There is a specific type of module called a `resource module` which inherits from 
the `\Snap\Admin\Modules\ResourceModule` and can generally be thought of as an easy way to 
provide CRUD functionality (among other things) for a model. A module's model should inherit from `Snap\Admin\Modules\ModuleModel`.
Resource modules can easily add powerful functionality through the use of traits like:
* [creating and editing (FormTrait)](#FormTrait)
* [table views (TableTrait)](#TableTrait)
* [filtering (FiltersTrait)](#FiltersTrait)
* [searching (SearchableTrait)](#SearchableTrait)
* [deleting (DeleatableTrait)](#DeleatableTrait)

[And more...](#ModuleTraits)


#### <a id="resource-modules"></a>Resource Module Properties and Methods ####
Resource modules have some additional properties and methods:

##### Properties #####
* `$model`: The full class name of the model.
* `$query`: The query object for referencing model information. It is normally accessed view the module's `getQuery()` method.
* `$ui`: A `ResourceModule` has the defaults of:
```
 protected $ui = [
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
        'input'            => 'module.resource.input',
        'compare'          => 'module.resource.compare',
    ];
```

##### Methods #####
* `getModel()`: Returns the model instance specified by the `$model` property.
* `getQuery()`: Returns the model's query builder instance.

---
### <a id="controllers"></a>Module Controllers ###
* @TODO




