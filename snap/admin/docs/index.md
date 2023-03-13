# SNAP Admin Package #

The SNAP Admin package will help you easily create complicated administrative backends for your Laravel projects.

* [Configuration](#configuration)
	* [Accessing](#accessing)
	* [Options](#options)
	* [Service Providers](#providers)
	* [Bootstrap File](#bootstrap)
* [Admin Directory](#directory)
* [Admin Routes](#routes)
* [Admin Facade](#facade)
* [Modules]({docs_url}/admin/modules)
	* [Traits]({docs_url}/admin/modules/traits)
	* [UI]({docs_url}/admin/modules/ui)

---

## <a id="configuration"></a>Configuration ##
The `admin` config is located at `config/snap/admin.php`. If it is not found there, be sure to publish the `Snap\Admin\AdminServiceProvider`.

```
php artisan vendor:publish --provider="Snap\Admin\AdminServiceProvider"
```

The following are the default values found in the config.
```
return [

    'title' => 'Admin',

    'route' => [
        'prefix'     => 'admin',
        'middleware' => ['web'],
        'namespace'  => 'App\Admin',
    ],

    'path' => app_path('Admin'),

    'login_redirect'  => 'dashboard',
    
    'logout_redirect' => 'admin/login',

    'date_format' => 'm/d/Y',

];
```

### <a id="accessing"></a>Accessing ###
Admin configuration items can be accessed a couple of ways:
* `config('snap.admin.{myoption})`
* `Admin::config('myoption')`

### <a id="options"></a>Options ###
* `title`: The name that appears in the upper left area of the admin interface. 
* `route.prefix`: The URI path to the admin area. Default is `admin`. 
* `route.middleware`: The middleware to apply to all routes that begin with `admin`. Default is `web`.
* `route.namespace`: The class namespace `App\Admin`. 
* `path`: The server path to the customizable Admin code such as module's and controllers. The default is `app_path('Admin')` which is usually `app/Admin`. 
* `login_redirect`: The URI path to redirect to after login. 
* `logout_redirect`: The URI path to redirect to after logout. 
* `date_format`: The date format to be used by modules by default. The default is `d/m/Y` or `01/01/2001`.


### <a id="providers"></a>Service Providers ###
In addition to the `config/snap/admin.php`, there are three service providers that can be configured in the `app\Admin\Providers`:

* `MenuServiceProvider`: Initializes the admin's menu structure. Modules will add their own menu items based on their setup.
* `ModulesServiceProvider`: Configures the modules that appear inside the admin.
* `UiServiceProvider`: Aliases names or "handles" to UI classes and can be used with the `ui()` function.

### <a id="bootstrap"></a>Bootstrap File ###
The `bootstrap.php` file is used for making global admin specific changes. By default, it includes the following:  

```
\Carbon\Carbon::setToStringFormat(config('snap.admin.date_format'));
```

---

## <a id="directory"></a>Admin Directory ##
The admin directory is by default located in the `app/Admin` folder. This folder is intended to house the customization code for the admin including custom controllers, models and modules and has the default structure of:
```
app/
  ↳ Admin/
    ↳ Controllers/
    ↳ Modules/
    - bootstrap.php
    - routes.php
```

---

## Admin Routes ##
The `app/Admin/routes.php` file contains the admin route definitions. By default it includes the following:
```
use Illuminate\Routing\Router;

Route::group([
    'prefix'        => config('snap.admin.route.prefix'),
    'namespace'     => config('snap.admin.route.namespace'),
    'middleware'    => config('snap.admin.route.middleware'),
], function (Router $router) {

	Admin::routes();

});
```

---

## Admin Facade ##
The admin package has an `Admin` facade with the following methods: 
* `Admin::routes()`: Generates all the routes for the admin which includes all routes defined in registered modules.
* `Admin::modules($module = null)`: Returns the module object(s) of the admin. If a `$module` handle is passed, it will return only that specific module.
* `Admin::ui($handle = null, $params = [])`: Creates a ui object with the `$handle` parameter being a `ui` mapped class key in the [admin config](config#options)
* `Admin::url($path = '')`: Returns the base path URL of the admin. If the `$path` parameter is passed, it will be appended to the url.
* `Admin::config($key, $default = null)`: Returns a configuration item using dot syntax for nested arrays. If no value exists, the `$default` value will be used.