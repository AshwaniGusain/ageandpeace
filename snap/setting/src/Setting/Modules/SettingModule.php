<?php

namespace Snap\Setting\Modules;

use Snap\Admin\Modules\Module;
use Snap\Setting\Http\Controllers\SettingModuleController;
use Snap\Setting\Ui\SettingsFormPage;

class SettingModule extends Module
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;

    protected $parent = null;

    protected $handle = 'setting';

    protected $name = 'Setting';

    protected $pluralName = 'Settings';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Settings';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-sliders';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $permissions = ['edit setting'];

    protected $config = null;

    protected $routes = [];

    protected $controller = SettingModuleController::class;

    protected $ui = [
        'settings'  => SettingsFormPage::class,
    ];

    public function register()
    {
        parent::register();
        $this->addRoute(['patch'], '/', '@update', []);
    }
}