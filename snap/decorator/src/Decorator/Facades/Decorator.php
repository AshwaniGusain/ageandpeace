<?php

namespace Snap\Decorator\Facades;

use Illuminate\Support\Facades\Facade;

class Decorator extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snap.decorator'; }

}