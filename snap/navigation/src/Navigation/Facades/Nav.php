<?php

namespace Snap\Navigation\Facades;

use Illuminate\Support\Facades\Facade;

class Nav extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snap.nav'; }

}