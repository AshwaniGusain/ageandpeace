<?php

namespace Snap\Core;

use Illuminate\Support\Facades\Facade;

class SNAP extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snap'; }

}