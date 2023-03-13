<?php

namespace Snap\Admin\Ui\Auth;

use Snap\Ui\UiComponent;

class PasswordResetRequestPage extends UiComponent {

    protected $view = 'admin::auth.passwords.email';
    protected $data = [
        'config:page_title' => 'snap.admin.title',
    ];

}