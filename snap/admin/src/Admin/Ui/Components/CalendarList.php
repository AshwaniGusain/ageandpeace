<?php

namespace Snap\Admin\Ui\Components;

use Calendar;
use MaddHatter\LaravelFullcalendar\Event;
use Snap\Ui\Traits\CssTrait;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class CalendarList extends UiComponent
{
    use JsTrait;
    use CssTrait;

    protected $view = 'admin::components.calendar-list';
    protected $scripts = [
        'assets/snap/vendor/moment/moment.min.js',
        'assets/snap/vendor/fullcalendar/fullcalendar.min.js',
        'assets/snap/js/components/resource/Calendar.js',
    ];

    protected $styles = [
        'assets/snap/vendor/fullcalendar/fullcalendar.min.css',
    ];

    protected $data = [
        'module'            => null,
        'items'             => [],
        'start_date_column' => 'start_date',
        'end_date_column'   => 'start_date',
        'options'           => [
            'header'      => [
                'left'   => 'prev,next today',
                'center' => 'title',
                'right'  => 'month,agendaWeek,agendaDay,listMonth',
            ],
            'navLinks'    => true,
            'editable'    => false,
            'eventLimit'  => true,
            'themeSystem' => 'bootstrap4',
        ],
    ];

    public function getItems()
    {
        return $this->module->getQuery()->get()->keyBy('id');
    }

    public function normalizeItems($items)
    {
        $events = [];
        foreach($items as $key => $item) {
            $events[] = $this->normalizeItem($item);
        }

        return $events;
    }

    public function normalizeItem($item)
    {
        if ($item instanceof Event) {
            return $item;
        }
        $title = method_exists($item, 'getDisplayNameAttribute') ? $item->getDisplayNameAttribute() : 'title';
        $allDay = method_exists($item, 'isAllDay') ? $item->isAllDay() : false;

        //$start = $this->module->getCalendarStartDate();
        //$end = $this->module->getCalendarEndDate();

        $start = $this->start_date_column;
        $end = $this->end_date_column;

        $calendar = new \MaddHatter\LaravelFullcalendar\Calendar();
        dd($calendar);
        return Calendar::event(
            $title,
            $allDay,
            $item->$start,
            $item->$end,
            $item->id
        );
    }

    protected function _render()
    {
        $events = $this->normalizeItems($this->getItems());
        $this->calendar = Calendar::addEvents($events)->setOptions($this->options);

        return parent::_render();
    }

}