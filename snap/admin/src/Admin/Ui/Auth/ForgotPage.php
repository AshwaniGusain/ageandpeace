<?php

namespace Snap\Admin\Ui\Auth;

use Snap\Ui\UiComponent;

class ForgotPage extends UiComponent {

    protected $view = 'admin::auth.forgot';
    protected $data = [
        'config:page_title' => 'snap.admin.title',
    ];

}