<?php

namespace Snap\Admin\Ui\Dashboard;

use Snap\Admin\Models\Session;
use Snap\Ui\Components\Bootstrap\Card;
use Snap\Ui\Components\BulletList;

class SessionsWidget extends DashboardWidget
{
    public static $name = 'sessions';

    protected $view = '';

    protected $data = [
        'hours' => 1,
        'limit' => 5,
    ];

    protected function _render()
    {
        $sessions = Session::whereNotNull('user_id')->limit($this->limit)
                           ->where('last_activity', '>', 'last_activity - (3600 * '.$this->hours.')')
                           ->orderBy('last_activity', 'desc')->get()->unique('user_id')
        ;
        $list = new BulletList();
        foreach ($sessions as $session) {
            $user = $session->user;
            if ($user) {
                $list->add($user->name.' - '.$session->last_activity->format(\Admin::config('datetime_format')));
            }
        }

        $card = new Card();
        $card->setHeader('<strong>Active Sessions</strong>')
             ->setBody($list)
            //->setFooter('This is the footer of the card')
        ;

        return $card;
    }
}