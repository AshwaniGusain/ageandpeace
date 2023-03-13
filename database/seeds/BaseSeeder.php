<?php

use Illuminate\Database\Seeder;

define('SEEDING', true);

class BaseSeeder extends Seeder
{
    public function indexModuleResource($resource)
    {
        $module = \Module::resource($resource);
        if ($module) {
            $indexService = $module->service('indexable');
            if ($indexService) {
                $module->service('indexable')->indexResource($resource);
            }
        }
    }
}
