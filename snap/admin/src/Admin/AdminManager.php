<?php

namespace Snap\Admin;

use Snap\Admin\Models\Role;
use Snap\Admin\Modules\ModuleManager;
use Snap\Ui\UiFactory;
use Spatie\Permission\Models\Permission;

class AdminManager
{
    protected $modules = [];

    public function __construct(ModuleManager $modules = null, UiFactory $ui = null)
    {
        $this->modules = $modules;
        $this->ui = $ui;

        $this->boot();
    }

    protected function boot()
    {
        if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
            $admin = $this;
            include($bootstrap);
        }
    }

    public function routes()
    {
        // Load Auth routes and other required admin routes.
        require(__DIR__.'/../../routes/web.php');

        // Then we load all the module specific routes.
        $this->modules()->routes();

        return $this;
    }

    public function modules($module = null)
    {
        if ($module) {
            return $this->modules->get($module);
        }

        return $this->modules;
    }

    public function module($module)
    {
        return $this->modules->get($module);
    }

    public function models($model = null)
    {
        $models = [];
        foreach ($this->modules as $module) {
            if (method_exists($module, 'getModel')) {
                $models[$module->handle()] = get_class($module->getModel());
            }
        }

        if ($model) {
            if (isset($models[$model])) {
                return $models[$model];
            }
        }

        return $models;
    }

    public function ui($handle = null, $params = [])
    {
        if ($handle) {
            if (! $this->ui->isBound($handle) && strpos($handle, '::') === false) {
                $handle = 'admin::'.$handle;
            }

            return $this->ui->make($handle, $params);
        }

        return $this->ui;
    }

    public function url($path = '')
    {
        $url = $this->config('route.prefix');
        if ($path) {
            $url .= '/'.trim($path);
        }

        return $url;
    }

    public function config($key, $default = null)
    {
        if ($key) {
            // Helper for concatenating the date and time formats together.
            if ($key == 'datetime_format') {
                return config('snap.admin.date_format').' '.config('snap.admin.time_format');
            }
            return config('snap.admin.'.$key, $default);
        }

        return config('snap.admin');
    }

    public function seedPermissions()
    {
        foreach ($this->modules() as $module) {
            $module->seedPermissions();
        }

        // Not needed because we have a gate created in the AuthServiceProvider.
        //$role = Role::firstOrCreate(['name' => 'super-admin']);
        //$role->givePermissionTo(Permission::all());
    }
}