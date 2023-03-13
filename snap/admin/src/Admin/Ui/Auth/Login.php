<?php

namespace Snap\Admin\Ui\Auth;

use Snap\Ui\UiComponent;

class Login extends UiComponent {

    protected $view = 'admin::auth.login';
    protected $data = [
        'config:page_title' => 'snap.admin.title',
        ':reset' => '\Snap\Admin\Ui\Auth\PasswordReset',
    ];

}