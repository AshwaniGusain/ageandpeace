<?php

namespace Snap\Admin\Ui\Dashboard;

use Snap\Admin\Models\Log;
use Snap\Ui\Components\Bootstrap\Card;
use Snap\Ui\Components\Bootstrap\Grid;
use Snap\Ui\Components\BulletList;
use Snap\Ui\Components\Link;

class ActivityWidget extends DashboardWidget
{
    public static $name = 'activity';

    protected $view = 'admin::module.dashboard';

    protected $data = [
        ':grid' => Grid::class,
        'limit' => 5,
    ];

    protected function _render()
    {
        $logs = Log::limit($this->limit)->orderBy('updated_at', 'desc')->get();

        $list = new BulletList();
        if ($logs->count()) {
            foreach ($logs as $log) {
                $message = ($log->level != 'info') ? $log->level.': '.$log->message : $log->message;
                $list->add($message.' - '.$log->updated_at->format(\Admin::config('datetime_format')));
            }
        } else {
            $list->add(trans('admin::dashboard.no_recent_activity'));
        }

        $card = new Card();
        $card->setHeader('<strong>Recent Activity</strong>')->setBody($list)
             ->setFooter(new Link(['href' => admin_url('log'), 'content' => 'View Logs &gt;']))
        ;

        return $card;
    }
}