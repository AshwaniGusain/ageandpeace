<?php

namespace Snap\Admin\Ui\Auth;

use Snap\Admin\Ui\BasePage;

class MePage extends BasePage
{
    protected $view = 'admin::auth.me';

    protected $data = [
        ':heading' => '\Snap\Admin\Ui\Components\Heading',
        ':buttons' => '\Snap\Admin\Ui\Components\FormButtonBar',
        ':alerts'  => '\Snap\Ui\Components\Bootstrap\Alerts',
        'user'     => null,
        ':form'    => '\Snap\Admin\Ui\Components\Form',
    ];

    public function initialize()
    {
        $this->form->setTemplate('admin::components.form');

        $this->heading->setTitle(trans('admin::auth.me_heading', ['name' => $this->user->name]));

        $this->form->setValues(['name' => $this->user->name, 'email' => $this->user->email])->addText('name')
                   ->addEmail('email')->addPassword('password')
        ;
    }
}