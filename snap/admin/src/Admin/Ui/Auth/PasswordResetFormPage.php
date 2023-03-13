<?php

namespace Snap\Admin\Ui\Auth;

use Snap\Ui\UiComponent;

class PasswordResetFormPage extends UiComponent {

    protected $view = 'admin::auth.passwords.reset';
    protected $data = [
        'config:page_title' => 'snap.admin.title',
    ];

}