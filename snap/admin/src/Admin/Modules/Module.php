<?php

namespace Snap\Admin\Modules;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use IteratorAggregate;
use Snap\Admin\Http\Controllers\ModuleController;
use Snap\Admin\Modules\Contracts\ModuleInterface;
use Snap\Admin\Modules\Contracts\ResourceModuleInterface;
use Snap\Support\Traits\HasBootableTraits;
use Snap\Support\Version;
use Snap\Admin\Models\Permission;

abstract class Module implements ModuleInterface, Countable, IteratorAggregate, ArrayAccess
{
    use HasBootableTraits;

    /**
     * @var string
     */
    protected $handle = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $pluralName = '';

    /**
     * @var null
     */
    protected $parent = null;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var null
     */
    protected $config = null;

    /**
     * @var string
     */
    protected $icon = 'fa fa-gear';

    /**
     * @var string
     */
    protected $path = __DIR__;

    /**
     * @var array
     */
    protected $modules = [];

    /**
     * @var null
     */
    protected $uri = null;

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var string
     */
    protected $permissionsGuard = null;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var string
     */
    protected $defaultRouteMethod = 'index';

    /**
     * @var string
     */
    protected $controller = ModuleController::class;

    /**
     * @var string
     */
    protected $migration;

    /**
     * @var array
     */
    protected $ui = [];

    /**
     * @var array
     */
    protected $uiCallbacks = [];

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Module constructor.
     *
     * @param null $props
     * @param null $parent
     */
    public function __construct($props = [], $parent = null)
    {
        $this->initializeProperties($props);
        $this->parent = $parent;
        $this->register();
        // This is done in the Controller
        //$this->boot();
    }

    /**
     * The register method is called on instantiation of the module and is used
     * to configure things early in the process such as routes and trait aliases.
     * Traits that have a method name of `register{TraitName}` will be called
     * in this method.
     */
    public function register()
    {
        $this->initializeSubModules();
        foreach ($this->traits() as $trait) {
            $class = class_basename($trait);
            $method = 'register'.$class;
            if (method_exists($this, $method)) {
                app()->call([$this, $method]);
            }
        }
    }

    /**
     * The boot method is called explicitly in the controller and is used to
     * initialize default values for the trait. Similar to the `register`
     * method, Traits that have a method name of `boot{TraitName}` will be
     * called in this method.
     */
    public function boot()
    {
        $this->bootTraits();

        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    /**
     * Allows for mass assigning properties to the module.
     * Requires a method that begins with "set" + Property.
     *
     * @param $props
     */
    public function initializeProperties($props = [])
    {
        if (is_array($props)) {
            foreach ($props as $key => $val) {
                $method = 'set' . studly_case($key);
                if (method_exists($this, $method)) {
                    $this->$method($val);
                }
            }
        }
    }

    /**
     * Initializes sub modules.
     */
    public function initializeSubModules()
    {
        if (!empty($this->modules)) {
            $modules = [];
            foreach ($this->modules as $module) {
                $module = ModuleManager::make($module, $this);
                $modules[$module->handle()] = $module;
            }

            $this->modules = $modules;
        }
    }

    /**
     * Returns the `handle` property by default if the property is provided.
     * Otherwise, it will look at the class name and generate one automatically.
     *
     * @return string
     */
    public function handle()
    {
        if (empty($this->handle)) {
            return Str::snake(preg_replace('#(.+)Module$#U', '$1', class_basename($this)));
        }

        return $this->handle;
    }

    /**
     * Returns the parent modules `handle` property if a parent module is
     * associated with the module.
     *
     * @return mixed
     */
    public function parentHandle()
    {
        if ($this->parent) {
            return $this->parent->handle();
        }

        return null;
    }

    /**
     * Returns the full handle path to the module by looking appending the
     * parent module's handle. Because module's implement the `ArrayAccess`
     * interface, this method provides the full array dot syntax path to
     * the module.
     *
     * @return string
     */
    public function fullHandle()
    {
        if ($this->parent) {
            return $this->parent->handle().'.'.$this->handle();
        }

        return $this->handle();
    }

    /**
     * Returns the parent module if it exists.
     *
     * @return mixed
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Returns the `name` property by default if the property is provided.
     * Otherwise, it will return a `ucwords` on the `handle()` methods
     * returned value (e.g. user = User).
     *
     * @return string
     */
    public function name()
    {
        if (empty($this->name)) {
            return ucwords(str_replace('_', ' ', ($this->handle())));
        }

        return $this->name;
    }

    /**
     * Sets the name of the module.
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the `pluralName` property by default if the property is provided.
     * Otherwise, it will return a `str_plural` on the `name()` methods
     * returned value (e.g. User = Users).
     *
     * @return string
     */
    public function pluralName()
    {
        if (empty($this->pluralName)) {
            return Str::plural($this->name());
        }

        return $this->pluralName;
    }

    /**
     * Sets the plural name of the module.
     *
     * @param $pluralName
     * @return $this
     */
    public function setPluralName($pluralName)
    {
        $this->pluralName = $pluralName;

        return $this;
    }

    /**
     * Returns the description property.
     *
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * Sets the description of the module.
     *
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns the icon property by default.
     *
     * @return string
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * Sets the icon of the module.
     *
     * @param $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }
    /**
     * Returns the controller's class name.
     *
     * @return string
     */
    public function getController()
    {
        return '\\'.ltrim($this->controller, '\\');
    }

    /**
     * Returns the admin URL for the module.
     *
     * @param string $uri
     * @param array $params
     * @param bool $qs
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url($uri = '', $params = [], $qs = false)
    {
        // Append instead of pass as argument so it doesn't encode things like {}
        // which are used as placeholders for Table Actions and elsewhere.
        // $url = url($this->fullUri($uri, $params));
        if (! is_array($params)) {
            $params = [$params];
        }

        // Look first to see if there is a named route and if so, use the route function.
        $routeName = $this->fullUri($uri);
        if (\Route::getRoutes()->getByName($routeName)) {
            $url = route($routeName, $params);
            // If no named route is found, then simply just create the URL using url().
        } else {
            $url = url($this->fullUri($uri, $params));
        }

        if ($qs === true || is_array($qs)) {

            if (is_array($qs)) {
                foreach ($qs as $key => $value) {
                    $qs[$key] = sprintf('%s=%s', $key, urlencode($value));
                }

                $qs = implode('&', $qs);
            } else {
                $qs = request()->getQueryString();
            }
            if ($qs) {
                $url = sprintf('%s?%s', rtrim($url, '?'), $qs);
            }
        }

        return $url;
    }

    /**
     * Returns the relative admin base URI for the module relative to the admin's URI path.
     *
     * @return string
     */
    public function baseUri()
    {
        if (! isset($this->uri)) {
            //$moduleUri = (! $this->parentHandle()) ? $this->handle() : str_replace('.', '/', $this->fullHandle());
            $moduleUri = $this->handle();
        } else {
            $moduleUri = $this->uri;
        }

        return trim($moduleUri, '/');
    }

    /**
     * Returns the full admin base URI for the module relative to the admin's URI path.
     *
     * @return string
     */
    public function fullBaseUri()
    {
        return trim(config('snap.admin.route.prefix').'/'.$this->baseUri(), '/');
    }

    /**
     * Returns the admin URI for the module relative to the admin's URI path.
     *
     * @param string $uri
     * @param array $params
     * @return string
     */
    public function uri($uri = '', $params = [])
    {
        // Look first to see if there is a named route and if so, use the route function.
        $moduleUri = $this->baseUri();
        $uri = trim($moduleUri, '/').'/'.trim($uri, '/');

        if ($params) {

            $routeName = config('snap.admin.route.prefix').'/'.$uri;

            if (\Route::getRoutes()->getByName($routeName)) {
                $uri = trim(route($routeName, $params, false), '/');
                // remove the first part of the URI
                $uri = substr($uri, strlen(config('snap.admin.route.prefix')) + 1);

                // If no named route is found, then simply just create the URL using url().
            } else {

                if (is_string($params)) {
                    $params = [$params];
                }

                if (!empty($params)) {
                    $uri = $uri.'/'.implode('/', $params);
                }

            }
        }

        return $uri;
    }

    /**
     * Returns the full admin URI for the module.
     *
     * @param string $uri
     * @param array $params
     * @return string
     */
    public function fullUri($uri = '', $params = [])
    {
        return trim(config('snap.admin.route.prefix').'/'.$this->uri($uri, $params), '/');
    }

    /**
     * Returns the admin module's current URI path (if currently on a module's
     * page in the admin).
     *
     * @return string
     */
    public function currentUri()
    {
        $uri = trim(str_replace($this->fullUri(), '', \Route::current()->uri()), '/');
        return $uri;
    }

    /**
     * Returns the admin module's full current URI path (if currently on a
     * module's page in the admin).
     *
     * @return string
     */
    public function currentFullUri()
    {
        $uri = trim(str_replace($this->fullUri(), '', \Route::current()->uri()), '/');
        return $this->fullUri($uri);
    }

    /**
     * Creates the key value to be used for a routes name.
     *
     * @param $uri
     * @return string
     */
    public function routeKey($uri)
    {
        return $this->fullUri($uri);
    }

    /**
     * Adds to the module's $route property array routing information to be
     * executed in the module's route() method.
     *
     * @param $verbs
     * @param $uri
     * @param $callback
     * @param array $options
     * @return $this
     */
    public function addRoute($verbs, $uri, $callback, $options = [])
    {
        $options['module'] = $this->fullHandle(); // Must use handle in order to cache routes otherwise we get a recursive error
        $options['uses'] = (strpos($callback, '@') === 0) ? $this->getController().$callback : $callback;
        if (empty($options['as'])) {
            $options['as'] = $this->routeKey($uri);
        } elseif (strpos($options['as'], '/') !== 0) {
            $options['as'] = $this->routeKey($options['as']);
        } else {
            $options['as'] = trim($options['as'], '/');
        }

        // Add any specific permissions.
        if (!empty($options['permission'])) {
            $options['middleware'] = $options['middleware'] ?? [];
            $options['middleware'][] = 'permission:view '.$this->permissionName($options['permission']);
        }

        if (strlen($uri) > 1 && strpos($uri, '/') === 0) {
            $this->router->match($verbs, $uri, $options);
            //$this->publicRoutes[] = [$verbs, $uri, $options];

        } else {

            // Add generic permission for a catchall to access any route on the module.
            $defaultPermission = 'permission:'.$this->defaultPermission();
            if (!isset($options['middleware']) || !in_array($defaultPermission, $options['middleware'])) {
                $options['middleware'][] = $defaultPermission;
            }

            $uri = $this->uri($uri);
            $params = [$verbs, $uri, $options];
            $this->routes[] = $params;
        }

        return $this;
    }

    /**
     * Creates the routes specified by the addRoute method using
     * Route::match(...). Called by Admin::routes().
     */
    public function routes()
    {
        if ($this->controller) {
            // Here we use the fullHandle() method instead of the uri() method because
            // the Route::group() method will contain the "admin" prefix.
            // $uri = str_replace('.', '/', $this->fullHandle());
            $this->addRoute(['get'], '/', $this->getController().'@'.$this->defaultRouteMethod, ['as' => 'index']);
        }

        // Here we load any routes specified on the module usually by addRoute().
        $router = app('router');
        foreach ($this->routes as $route) {

            // Not recommended because the routes won't be able to cache
            if ($route instanceof \Closure) {
                call_user_func($route, $router, $this);
            } else {
                call_user_func_array([$router, 'match'], $route);
            }
        }

        // Then, we'll load any sub module routes.
        if ($this->modules) {
            foreach ($this->modules() as $module) {
                $module->routes();
            }
        }
    }

    ///**
    // * Placeholder for public routes
    // */
    //public function publicRoutes()
    //{
    //
    //}

    /**
     * Returns the module class's namespace.
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getNamespace()
    {
        $class = new \ReflectionClass($this);

        return $class->getNamespaceName();
    }

    /**
     * Convenience alias to getConfig().
     *
     * @param null $key
     * @param null $default
     * @return null
     */
    public function config($key = null, $default = null)
    {
        return $this->getConfig($key, $default);
    }

    /**
     * Returns a configuration value with the option to specify a default if the config value doesn't exist.
     *
     * @param null $key
     * @param null $default
     * @return null
     */
    public function getConfig($key = null, $default = null)
    {
        if (isset($key)) {
            if (isset($this->config->$key)) {
                return $this->config->$key;
            }

            return $default;
        }

        return $this->config;
    }

    /**
     * Sets a config value on the module.
     *
     * @param $key
     * @param null $val
     * @return $this
     */
    public function setConfig($key, $val = null)
    {
        if (is_array($key)) {
            $this->config = $key;
        } elseif (is_string($key)) {
            $this->config[$key] = $val;
        }

        return $this;
    }

    /**
     * Returns the server path to the module class.
     *
     * @param null $file
     * @return string
     */
    public function path($file = null)
    {
        $path = rtrim($this->path, '/');

        if ($file) {
            $path .= '/'.$file;
        }

        return $path;
    }

    /**
     * Returns an array of any sub modules that may be associated with the module.
     *
     * @return array
     */
    public function modules()
    {
        //foreach ($this->modules as $module) {
        //    $this->module($module->handle());
        //}

        return $this->modules;
    }

    /**
     * Returns a single sub module based on the handle passed to the method.
     *
     * @param $handle
     * @return mixed
     */
    public function module($handle)
    {
        // This was used to lazy load the modules... but only works when the array has the key for a handle on the module.
        //static $modules = [];

        //if (isset($modules[$handle])) {
        //    $module = $modules[$handle];
        //} elseif (is_subclass_of($handle, ModuleInterface::class)){
        //    $module = ModuleManager::make($handle);
        //    $modules[$module->handle()] = $module;
        //}
        //if (isset($this->modules[$handle])) {
        //    if (! is_object($this->modules[$handle])) {
        //        $modules = config('snap.admin.modules');
        //        $overwriteHandle = substr($this->fullHandle().'.'.$handle, 5);
        //        $class = (isset($modules[$overwriteHandle])) ? $modules[$overwriteHandle] : $this->modules[$handle];
        //        // $handleArr = explode('.', $handle);
        //        // $handle = end($handleArr);
        //        //$this->attach($class, $handle);
        //        $this->modules[$handle] = new $class([], $this);
        //    }
        //    // return $this->modules[$handle];
        //}
        if (isset($this->module)) {
            return Arr::get($this, $handle);
        }

        return null;
    }

    /**
     * Returns a boolean value based on if the currently viewed module is this module.
     *
     * @return bool
     */
    public function isCurrent()
    {
        return \Admin::modules()->current() == $this;
    }

    /**
     * Returns a UI object for rendering.
     *
     * @param $handle
     * @param array $params
     * @param null $callback
     * @return mixed|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function ui($handle, $params = [], $callback = null)
    {
        if (isset($this->ui[$handle])) {

            if ($params instanceof Closure) {
                $callback = $params;
                $params = [];
            }

            if (is_array($params)) {
                $params['module'] = $this;
            }

            $ui = app('snap.ui')->make($this->ui[$handle], $params, $callback);

            // Here we allow for customization of UI objects from within the module
            // if a method of "ui" + handle is specified on the module.
            $method = 'ui'.ucfirst(Str::camel(Str::slug($handle)));

            if (method_exists($this, $method)) {
                app()->call([$this, $method], ['ui' => $ui]);
            }

            return $ui;
        }

        return null;
    }

    /**
     * Creates event callbacks for UI elements.
     *
     * @param $handles
     * @param \Closure $callback
     * @param string $event
     * @return $this
     */
    public function addUiCallback($handles, Closure $callback, $event = 'initialized')
    {
        if (is_string($handles)) {
            $handles = [$handles];
        }
        foreach ($handles as $handle) {
            if (isset($this->ui[$handle])) {
                $class = app('snap.ui')->boundClass($this->ui[$handle]);
            } else {
                $class =  $handle;
            }

            if (class_exists($class)) {
                Event::listen([$class::eventName($event)], function($ui) use ($callback) {
                    $callback($ui, request(), $this);
                });
            }
        }

        return $this;
    }

    /**
     * Adds a UI handle and class to the module.
     *
     * @param $handle
     * @param $class
     * @return $this
     */
    public function aliasUi($handle, $class = null)
    {
        if (is_array($handle)) {
            foreach ($handle as $h => $c) {
                $this->aliasUi($h, $c);
            }
        } else {
            $this->ui[$handle] = $class;
        }

        return $this;
    }

    /**
     * Determines if a UI alias already exists or not.
     *
     * @param $handle
     * @return bool
     */
    public function hasUiAlias($handle)
    {
        return isset($this->ui[$handle]);
    }

    /**
     * Returns the full event name for  Event::listen().
     *
     * @param $name
     * @return string
     */
    public static function eventName($name)
    {
        return 'module.' . $name . ': ' . static::class;
    }

    /**
     * Returns the permissions property by default.
     *
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Returns a boolean based on if the currently logged in user has permission to view the module.
     *
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $user = \Auth::user();
        $permission = isset($this->permissions[$permission]) ? $this->permissions[$permission] : $permission;

        return ($user->can($permission));
    }

    /**
     * Returns the permission name.
     *
     * @param $name
     * @return string
     */
    public function permissionName($name)
    {
        return $name.' '.$this->handle();
    }

    /**
     * Adds multiple permissions to the module.
     *
     * @param $permissions
     * @return $this
     */
    public function addPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }

        return $this;
    }

    /**
     * Adds a permission to the module.
     *
     * @param $name
     * @return $this
     */
    public function addPermission($name)
    {
        if (!isset($this->permissions[$name])) {
            $this->permissions[$name] = $this->permissionName($name);
        }

        return $this;
    }

    /**
     * Returns the default permission for the module.
     *
     * @return string
     */
    public function defaultPermission()
    {
        return 'view '.$this->handle();
    }

    /**
     * Assigns one ore more module permissions to one or more users/roles.
     *
     * @param $entities
     * @param $permissions
     * @param $children
     * @return $this
     */
    public function givePermissionTo($entities, $permissions = [], $children = false)
    {
        if (!is_array($entities)) {
            $entities = [$entities];
        }

        $fullPermissions = [];
        if (empty($permissions)) {
            $fullPermissions = $this->permissions;
        } else {
            foreach ($permissions as $k => $permission) {
                $fullPermissions[$k] = $this->permissionName($permission);
            }
        }

        foreach ($entities as $entity) {
            foreach ($fullPermissions as $permission) {
                $entity->givePermissionTo($permission);
            }

            if ($children) {
                foreach ($this->modules() as $module) {
                    $module->givePermissionTo($entity, $permissions);
                }
            }
        }

        return $this;
    }

    /**
     * Registers a traits services.
     *
     * @param $key
     * @param $service
     * @param $callback
     * @return \Snap\Admin\Modules\Module
     */
    public function bindService($key, $service, $callback = true)
    {
        $this->services[$key][0] = $service;

        if ($callback === true) {
            $callback = [$this, $key];
        } elseif (is_string($callback)) {
            $callback = [$this, $callback];
        }

        $this->services[$key][1] = $callback;

        return $this;
    }

    /**
     * Returns a registered service.
     *
     * @param $key
     * @return mixed
     */
    public function service($key)
    {
        // Initialization of the service only happens once.
        if (isset($this->services[$key])) {
            if (is_array($this->services[$key])) {
                $service = $this->services[$key][0];
                $callback = $this->services[$key][1];

                // This is used for lazy service loading.
                if ($service instanceof \Closure) {
                    $this->services[$key] = call_user_func($service, $this);
                } else {
                    $this->services[$key] = $service;
                }

                if (!empty($callback) && is_callable($callback)) {
                    call_user_func($callback, $this->services[$key], request());
                }
            }

            return $this->services[$key];
        }

        return null;
    }

    /**
     * Returns an array of all the attached services
     *
     * @return array
     */
    public function services()
    {
        return $this->services;
    }

    /**
     * Determines if a service is available for a module.
     *
     * @param $key
     * @return bool
     */
    public function hasService($key)
    {
        return isset($this->services[$key]);
    }

    /**
     * Returns the version property.
     *
     * @return \Snap\Support\Version
     */
    public function version()
    {
        return new Version($this->version);
    }

    /**
     * Determines whether the module is a resource module.
     *
     * @return bool
     */
    public function isResource()
    {
        return $this instanceof ResourceModuleInterface;
    }

    /**
     * Runs an event hook.
     *
     * @param $hook
     * @param array $params
     * @return mixed|null
     */
    public function runHook($hook, $params = [])
    {
        // Grab parameters to pass to the hook and event.
        if (! is_array($params)) {
            $params = array_shift(func_get_args());
        }

        // Fire the event for those that wish to hook into the event system instead.
        event($this->eventName($hook), $params);

        // First look for a method on the controller to call.
        if (method_exists($this, $hook)) {
            return call_user_func_array([$this, $hook], $params);
        }

        return null;
    }

    /**
     * Module specific installation code.
     */
    public function install()
    {
        $this->seedPermissions();
    }

    /**
     * Installs permissions for the module.
     */
    public function seedPermissions()
    {
        if ($permissions = $this->permissions()) {

            foreach ($permissions as $permission) {

                $params['name'] = $permission;
                if ($this->permissionsGuard) {
                    $params['guard_name'] = $this->permissionsGuard;
                }
                Permission::create($params);
            }
        }

        // Then, we'll seed any sub module permissions.
        if ($this->modules) {
            foreach ($this->modules() as $module) {
                $module->seedPermissions();
            }
        }
    }

    /**
     * @TODO: Module specific uninstallation code.
     */
    public function uninstall()
    {
        $this->removePermissions();
    }

    /**
     * Uninstalls permissions for the module.
     */
    public function removePermissions()
    {
        if ($permissions = $this->permissions()) {
            foreach ($permissions as $permission) {
                $params['name'] = $permission;
                if ($this->permissionsGuard) {
                    $params['guard_name'] = $this->permissionsGuard;
                }
                Permission::delete($params);
            }
        }

        // Then, we'll remove any sub module permissions.
        if ($this->modules) {
            foreach ($this->modules() as $module) {
                $module->removePermissions();
            }
        }
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->modules());
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->modules());
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->modules);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->modules[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->modules[$key] = $value;
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->modules[$key]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        // $method = 'get'.str_replace(' ', '', humanize($name));
        $method = 'get'.ucfirst(Str::camel($name));

        if (method_exists($this, $method)) {
            return $this->$method();
        } elseif (isset($this->modules[$name])) {
            return $this->module($name);
        }

        throw new \RuntimeException("Property {$name} does not exist");
    }


    /**
     * @param $name
     * @param $val
     * @return $this
     */
    public function __set($name, $val)
    {
        $method = 'set'.ucfirst(Str::camel($name));

        if (method_exists($this, $method)) {
            $this->$method($val);

            return $this;
        }

        throw new \RuntimeException("Property {$name} does not exist");
    }
}