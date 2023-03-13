<?php

namespace Snap\Setting\Ui;

use Snap\Admin\Ui\BasePage;
use Snap\Form\Label;

class SettingsFormPage extends BasePage
{
    protected $view = 'settings::settings-form';

    protected $data = [
        'module'   => null,
        ':heading' => '\Snap\Admin\Ui\Components\Heading[module]',
        ':alerts'        => '\Snap\Ui\Components\Bootstrap\Alerts',
        'form'     => null,
    ];

    public function initialize()
    {
        $this->form = \Form::make();
        //$this->form->scope('settings[]');
        $settings = config('snap.settings');

        $group = null;
        foreach ($settings as $key => $input) {
            if (is_int($key)) {
                // Key is a field group
                $group = $input;
                continue;
            }

            if (! empty($group)) {
                $input['group'] = $group;
            }

            if (empty($input['label'])) {
                $keyParts = explode('.', $key);
                $label = end($keyParts);
                $input['label'] = Label::convertNameToLabel($label);
            }

            $input['value'] = setting($key);

            $key = str_replace('.', '-', $key);
            $this->form->add($key, $input);
        }

        $this->heading->setTitle(trans('settings::messages.page_title'));

        $this->setPageTitle([trans('settings::messages.page_title')]);

    }
}