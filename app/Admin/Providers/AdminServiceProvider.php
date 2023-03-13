<?php

namespace App\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use \Snap\DataTable\DataTable;

class AdminServiceProvider extends ServiceProvider
{
    protected $providers = [

    ];

    public function register()
    {
        $this->registerServiceProviders();
    }

    public function boot()
    {
        DataTable::addGlobalFormatters([
            '\Snap\DataTable\DataTableFormatters::dateFormatter',
            '\Snap\DataTable\DataTableFormatters::booleanFormatter' => ['active']
        ]);

        //\Admin::modules()->get('taxonomy')->setMenuParent('admin');
    }

    protected function registerServiceProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}