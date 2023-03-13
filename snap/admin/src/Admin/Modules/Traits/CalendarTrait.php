<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\CalendarService;
use Snap\Admin\Ui\Components\CalendarList;
use Snap\Ui\UiComponent;

trait CalendarTrait {

    public function registerCalendarTrait()
    {
        $this->aliasTrait('calendar', 'Snap\Admin\Modules\Traits\CalendarTrait');

        $this->addRoute(['get'], 'calendar', '@calendar', []);
    }

    public function bootCalendarTrait()
    {
        $this->bindService('calendar', function(){
            return CalendarService::make($this);
        });

        $this->addUiCallback(['calendar'], function ($ui, $request, $module) {
            $service = $this->service('calendar');
            //$this->calendar($service, $request);
            $ui->calendar
                ->setStartDateColumn($service->startDateColumn)
                ->setEndDateColumn($service->endDateColumn)
                ;
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function calendar(CalendarService $ajax, Request $request)
    {

    }

}