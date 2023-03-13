<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Ui\Traits\JsTrait;

class FormInlinePage extends FormPage
{
    use JsTrait;

    protected $view = 'admin::module.resource.edit-inline';
    protected $scripts = [
       // 'assets/snap/js/components/Inline.js',
    ];

    public function initialize()
    {
        parent::initialize();
        $this->setInline(true);
        $this->heading->setBack(false)->setCreate(false)->setModal(true);
        $this->form->add('__inline__', 'hidden', ['value' => 1]);
    }
}