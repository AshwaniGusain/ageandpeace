<?php

namespace Snap\Docs\Facades;

use Illuminate\Support\Facades\Facade;

class Docs extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'docs'; }

}