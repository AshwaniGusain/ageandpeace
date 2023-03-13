<?php

namespace Snap\Admin\Modules\Traits;

use Event;
use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\LogService;

trait LogTrait {

    public function registerLogTrait()
    {
        $this->aliasTrait('log', 'Snap\Admin\Modules\Traits\LogTrait');
    }

    public function bootLogTrait()
    {
        $this->bindService('log', function(){
            return LogService::make($this);
        });

        Event::listen(static::eventName('afterSave'), function($resource){
            $this->service('log')->save($resource);
        });

    }

    protected function log(LogService $listing, Request $request)
    {

    }

}
