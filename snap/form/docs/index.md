# SNAP Form Package #

The SNAP Form package allows you to easily create forms and out of the box has a number of input types for you to choose from.
If the package doesn't already provide you with what you need, you can easily create your own input types.

* [Configuration](#configuration)
* [Form Facade](#facade)
* [Form Helpers](#helpers)
* [Form Instance]({docs_url}/auto?c=Snap\Form\Form)
* [Inputs]({docs_url}/form/inputs)

---

## <a id="configuration"></a>Configuration ##
The `form` package config is located at `config/snap/forms.php`. If it is not found there, be sure to publish the `Snap\Form\FormServiceProvider`.


```
php artisan vendor:publish --provider="Snap\Form\FormServiceProvider"
```

The config file provides mappings to different input types which are classes that inherit from `Snap\Form\Inputs\BaseInput` abstract class.
Additionally, there are `hints` for the mapping model field names or field data types to inputs (see `\Form::model`).
The following are the default values found in the config.
```
return [

    'types' => [
            'hidden'        => \Snap\Form\Inputs\Hidden::class,
            'text'          => \Snap\Form\Inputs\Text::class,
            'boolean'       => \Snap\Form\Inputs\Checkbox::class,
            'password'      => \Snap\Form\Inputs\Password::class,
            'textarea'      => \Snap\Form\Inputs\Textarea::class,
            'number'        => \Snap\Form\Inputs\Number::class,
            'date'          => \Snap\Form\Inputs\Date::class,
            'datetime'      => \Snap\Form\Inputs\DateTime::class,
            'time'          => \Snap\Form\Inputs\Time::class,
            'timezone'      => \Snap\Form\Inputs\TimeZone::class,
            'range'         => \Snap\Form\Inputs\Range::class,
            'slug'          => \Snap\Form\Inputs\Slug::class,
            'email'         => \Snap\Form\Inputs\Email::class,
            'phone'         => \Snap\Form\Inputs\Phone::class,
            'color'         => \Snap\Form\Inputs\Color::class,
            'multiradio'    => \Snap\Form\Inputs\MultiRadio::class,
            'multicheckbox' => \Snap\Form\Inputs\MultiCheckbox::class,
            'select'        => \Snap\Form\Inputs\Select::class,
            'belongsto'     => \Snap\Admin\Ui\Components\Inputs\BelongsTo::class,
            'belongstomany' => \Snap\Admin\Ui\Components\Inputs\BelongsToMany::class,
            'morphtomany'   => \Snap\Admin\Ui\Components\Inputs\MorphToMany::class,
            'morphmany'   => \Snap\Admin\Ui\Components\Inputs\MorphMany::class,
            'state'         => \Snap\Form\Inputs\State::class,
            'multiselect'   => \Snap\Form\Inputs\Select2::class,
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
            'currency'      => \Snap\Form\Inputs\Currency::class,
            'between'       => \Snap\Form\Inputs\Between::class,
            'custom'        => \Snap\Form\Inputs\Custom::class,
            'template'      => \Snap\Website\Ui\Inputs\Template::class,
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
                'file'     => ['file', 'image'],
                'taxonomy' => ['taxonomy', 'tags'],
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
```

---

## <a id="facade"></a>Form Facade ##
The form package has a `Form` facade with the following methods: 
* `Form::make($elements = [])`: 
* `Form::model($model, $options = [])`:
* `Form::element($name, $type = 'text', $props = [])`:
* `Form::hint($name, $type = 'text', $props = [])`:
* `Form::bind($type, $config = null)`:
* `Form::getBoundClass($type)`:
* `Form::hasBinding($type)`: 
* `Form::getDefaultProps($type)`: 

---

## <a id="helpers"></a>Form Helpers ##
The form package has a `Form` facade with the following methods: 
* `form($elements = [])`: Alias to `Form::make()`.
* `form_model($model, $options = [])`: Alias to `Form::model()`.
* `form_element($name, $type = 'text', $props = [])`: Alias to `Form::element()`.