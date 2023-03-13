<?php

namespace Snap\Analytics\Ui\Dashboard;

use Analytics;
use Snap\Admin\Ui\Dashboard\DashboardWidget;
use Spatie\Analytics\Period;

class AnalyticsWidget extends DashboardWidget
{
    public static $name = 'analytics';

    protected $view = 'admin::components.chart';

    protected $data = [
        'width'   => 200,
        'height'  => 300,
        'dataset' => [],
        'labels'  => [],
        'title'   => 'Visitors Last 7 Days',
    ];

    protected function _render()
    {
        $this->initAnalyticsData();

        return parent::_render();
    }

    protected function initAnalyticsData()
    {
        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
        $data = [];
        $labels = [];
        foreach ($analyticsData as $d) {
            $data[] = ['t' => $d['date']->format('Y-m-d'), 'y' => $d['visitors']];
            $labels[] = $d['date']->format('Y-m-d');
        }

        $this->data['dataset']['data'] = $data;
        $this->data['dataset']['label'] = $this->title;
        $this->data['dataset']['borderWidth'] = 1;
        $this->data['dataset'] = json_encode($this->data['dataset']);

        $this->data['labels'] = json_encode($labels);

        return $data;
    }
}