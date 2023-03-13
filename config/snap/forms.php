<?php
// Copy to config/snap/forms.php
return [
    'types' => [
        'input'         => \Snap\Form\Inputs\Input::class,
        'hidden'        => \Snap\Form\Inputs\Hidden::class,
        'text'          => \Snap\Form\Inputs\Text::class,
        'checkbox'      => \Snap\Form\Inputs\Checkbox::class,
        'boolean'       => \Snap\Form\Inputs\SwitchInput::class,
        'password'      => \Snap\Form\Inputs\Password::class,
        'textarea'      => \Snap\Form\Inputs\Textarea::class,
        'number'        => \Snap\Form\Inputs\Number::class,
        'date'          => \Snap\Form\Inputs\Date::class,
        'datetime'      => \Snap\Form\Inputs\DateTime::class,
        'time'          => \Snap\Form\Inputs\Time::class,
        'timezone'      => \Snap\Form\Inputs\TimeZone::class,
        'range'         => \Snap\Form\Inputs\Range::class,
        'slug'          => \Snap\Form\Inputs\Slug::class,
        'mirror'        => \Snap\Form\Inputs\Mirror::class,
        'email'         => \Snap\Form\Inputs\Email::class,
        'phone'         => \Snap\Form\Inputs\Phone::class,
        'color'         => \Snap\Form\Inputs\Color::class,
        'multiradio'    => \Snap\Form\Inputs\MultiRadio::class,
        'multicheckbox' => \Snap\Form\Inputs\MultiCheckbox::class,
        'select'        => \Snap\Form\Inputs\Select::class,
        //'belongsto'     => \Snap\Form\Inputs\BelongsTo::class,
        'belongsto'     => \Snap\Admin\Ui\Components\Inputs\BelongsTo::class,
        //'hasmany'       => \Snap\Form\Inputs\HasMany::class,
        'hasmany'       => \Snap\Admin\Ui\Components\Inputs\HasMany::class,
        //'belongstomany' => \Snap\Form\Inputs\BelongsToMany::class,
        'belongstomany' => \Snap\Admin\Ui\Components\Inputs\BelongsToMany::class,
        //'morphtomany'   => \Snap\Form\Inputs\MorphToMany::class,
        'morphtomany'   => \Snap\Admin\Ui\Components\Inputs\MorphToMany::class,
        //'morphmany'     => \Snap\Form\Inputs\MorphMany::class,
        'morphmany'     => \Snap\Admin\Ui\Components\Inputs\MorphMany::class,
        'state'         => \Snap\Form\Inputs\State::class,
        'multiselect'   => \Snap\Form\Inputs\DualMultiSelects::class,
        'select2'       => \Snap\Form\Inputs\Select2::class,
        'tag'           => \Snap\Form\Inputs\Tag::class,
        'repeatable'    => \Snap\Form\Inputs\Repeatable::class,
        'file'          => \Snap\Form\Inputs\File::class,
        'coordinates'   => \Snap\Form\Inputs\Coordinates::class,
        'keyvalue'      => \Snap\Form\Inputs\KeyValue::class,
        'dependent'     => \Snap\Form\Inputs\Dependent::class,
        'list'          => \Snap\Form\Inputs\ListInput::class,
        'toggle'        => \Snap\Form\Inputs\Toggle::class,
        'url'           => \Snap\Form\Inputs\Url::class,
        'media'         => \Snap\Media\Ui\Components\Inputs\Media::class,
        'taxonomy'      => \Snap\Taxonomy\Ui\Taxonomy::class,
        'wysiwyg'       => [
            'class'  => \Snap\Form\Inputs\Wysiwyg::class,
            'config' => [
                'attrs' => [
                    ':options' => [
                        'minHeight' => '200px',
                        'maxHeight' => '800px',
                        //'plugins'          => [
                        //    // 'filemanager',
                        //    'snapmediamanager',
                        //    //'textexpander',
                        //    'definedlinks',
                        //    //'limiter',
                        //    //'textexpander',
                        //    //'variable'
                        //],
                        //'fileUpload'       => '/admin/media/upload',
                        //'fileManagerJson'  => '/admin/media/files.json',
                        //'imageUpload'      => admin_url('/admin/media/upload'),
                        //'imageManagerJson' => admin_url('/admin/media/images.json'),
                        //'definedlinks'     => [],
                    ],
                ],

            ],
        ],
        'table'         => \Snap\Form\Inputs\Table::class,
        'listing'       => \Snap\Form\Inputs\HierarchicalListing::class,
        'currency'      => \Snap\Form\Inputs\Currency::class,
        'between'       => \Snap\Form\Inputs\Between::class,
        'custom'        => \Snap\Form\Inputs\Custom::class,
        'template'      => \Snap\Page\Ui\Inputs\Template::class,
        'submit'        => \Snap\Form\Buttons\Submit::class,
        'button'        => \Snap\Form\Buttons\Button::class,
        'reset'         => \Snap\Form\Buttons\Reset::class,
        'buttonlink'    => \Snap\Form\Buttons\ButtonLink::class,
    ],

    'hints' => [
        'name' => [
            'hidden'   => 'id',
            'phone'    => ['phone', 'telephone', 'tel'],
            'password' => ['password', 'pwd', 'passwd', 'new_password', 'new_pwd', 'new_passwd'],
            'email'    => 'email',
            'color'    => 'color',
            'slug'     => ['slug', 'permalink'],
            'currency' => ['price', 'cost'],
            'wysiwyg'  => ['body'],
            'state'    => ['state'],
            'boolean'  => ['active'],
            'timezone' => ['timezone'],
            'file'     => ['file'],
            'media'    => ['image', 'images', 'logo'],
            'taxonomy' => ['taxonomy', 'tags'],
            'url'      => ['url'],
        ],
        'type' => [
            'date'        => 'date',
            'datetime'    => ['datetime', 'timestamp'],
            'time'        => ['time'],
            'boolean'     => ['boolean'],
            'number'      => ['tinyint', 'int', 'smallint', 'medint', 'bigint'],
            'textarea'    => ['text'],
            'select'      => ['enum'],
            'coordinates' => ['point'],
        ],
    ],
];