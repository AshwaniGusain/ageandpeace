<?php

namespace Snap\Ui\Facades;

use Illuminate\Support\Facades\Facade;

class UI extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snap.ui'; }

}