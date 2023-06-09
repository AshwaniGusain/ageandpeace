<?php

namespace Snap\Media\Facades;

use Illuminate\Support\Facades\Facade;

class Media extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snap.media'; }

}