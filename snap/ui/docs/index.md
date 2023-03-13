# SNAP UI Package #

The SNAP UI package provides a powerful alternative to Laravel's `View` facade and `view()` function by providing a `Snap\UI\UIComponent` class instead.
This helps encapsulate the logic for rendering your views in a class similar to using [Laravel's view composers](https://laravel.com/docs/5.7/views#view-composers) but with some added functionality.
The `UIComponent` class provides the following:

* Automatic variable transformation to different data types. For example, a UI component may contain nested UI components and specifying a data variable of type `ui` will automatically instantiate the UI component object. 
* Conditional rendering. For example, the same component can be used for rendering both an AJAX view and a normal non-AJAX view.
* Parent UI component access. For example, a child nested component can access a parent UI components data variable to facilitate it's own rendering.
* Conditional visibility display. For example, a component may need to be displayed based on a user's permissions.

The UI package has a corresponding configuration file at `config/snap/ui.php`.

---

## A UIComponent Example ##
Below is an example of a simple `UIComponent` for a search box:
```
<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\UiComponent;
use Illuminate\Http\Request;

class Search extends UiComponent
{
    protected $view = 'admin::components.search';

    protected $data = [
        'q' => null,
    ];

    public function initialize(Request $request) {
        $this->q = $request->get('q');
    }
}
```

### Initialization ###
Note the `initialize()` method in the example. This method is used to establish default properties and will automatically inject from [Laravel's IOC container](https://laravel.com/docs/5.7/container).
In this case, the `$request` object is injected and is used to set the `$this->q`.

### Data ###
The `UIComponent` class implements magic methods that map any undefined class properties without corresponding `set{KEY}($value)` (such as `$q` in this example) to the `$data` array keys (or `$this->data['q']` in this example).
This means the following are both valid ways of setting and getting the `q` data value on the `Search` component:
```
$search = new Search();
$search->setQ('test1'); // note that a setQ() method does not need to be implemented
echo $search->getQ(); // note that a getQ() method does not need to be implemented
// "test1"

$search->q = 'test2'; // note that no "q" property is set on the class and is instead in the $data property
echo $search->q;
// "test2"
```
If the `$q` value needed to be modified before setting (e.g. stripping unwanted characters), a `setQ($value)` could be implemented that manually sets the `q` value on the `$data` property like so:
```
public function setQ($value)
{
	$this->data['q'] = strip_unwanted_characters($value);
	
	return $this;
}

```
_NOTE:`strip_unwanted_characters()` is a made up function._

### Rendering ###
A `UIComponent` has a `render()` method which is in charge of rendering and does the following:
* Checks if the visible property is set to `true`. If not, it won't render.
* If the visible property is set to `true`, it runs through the `renderers` (see the `addRenderer()` method) which are a way to establish different rendering behavior based on conditional logic.

The default renderer is the `_render()` method (note the `_`) that will check if there is a `$view` property set and if so, will generate the view by passing the `$data` property as values.
In the above example, `$q` is the only variable passed to a Blade template at `admin::components.search`. 
Sometimes you may need to override the `_render()` method (AND NOT `render()`) to set additional data variables before rendering.

The following is the contents of the `admin::components.search` Blade template for reference:
```
<input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search" aria-label="Search">
<div class="input-group-append">
	<button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i></button>
</div>
```

---

## Default Properties ##
* `$view`: The path to a view file to be used.
* `$data`: An array of data to pass to the view.

**IMPORTANT: If a `UIComponent` class is extended, the `$data` property from the parent class will be merged in instead of overwritten to simplify inheriting `$data` properties.** 

---

## Default Methods ##
* `render()`:
* `setView($view)`:
* `getView()`:
* `getData()`:
* `setData($key, $value = null)`
* `with($key, $value = null)`: Alias to `setData`
* `visible($bool)`: Sets the visibility of the component.
* `isVisible()`: Returns whether a component is visible.
* `root`: Returns the root `UIComponent` if nested
* `parent`: Returns the parent `UIComponent` if nested
* `setParent($component)`: Sets the parent `UIComponent` if nested
* `addRenderer($condition, $callback, $index = null)`:
* `removeRenderer($index)`:
* `compose(View $view)`
* `toHtml()`
* `eventName($name)`: (static method)

---

## UIComponent Interfaces ##
A `UIComponent` implements the following interfaces:
* `Snap\Support\Contracts\ToString`: This interface ensures that the [__toString()](http://php.net/manual/en/language.oop5.magic.php#object.tostring) magic method is implemented.
* `Illuminate\Contracts\Support\Htmlable`: This is Laravel's interface for generating an HTML string and ensures that the [toHtml()](https://laravel.com/api/5.6/Illuminate/Contracts/Support/Htmlable.html).

Additionally, the `UIComponent` class has a `compose()` method in case you want to use it as a [Laravel View Composer](https://laravel.com/docs/5.7/views#view-composers).

---

## UIComponent Events ##
The following are the events a component will trigger:
* initializing
* initialized
* beforeRender
* afterRender
The event name signature is `ui.{EVENT NAME}.{CLASS NAME}`. To simplify getting the event name, you can use the classes static `eventName($name)` method like so:
```
Event::listen(Search::eventName('initialized'), function($ui) {
	$ui->q = 'test';
});
```
---

## UI Config ##
The `config/snap/ui.php` file contains a couple UI configuration options:
```
'data_types' => [
	':' => 'Snap\Ui\DataTypes\ObjectType',
	'object' => 'Snap\Ui\DataTypes\ObjectType',
	'config' => 'Snap\Ui\DataTypes\ConfigType',
	'view' => 'Snap\Ui\DataTypes\ViewType',
],
    
'bindings' => [
        'login'                            => \Snap\Admin\Ui\Auth\Login::class,
        'auth.password_reset_request'      => \Snap\Admin\Ui\Auth\PasswordResetRequestPage::class,
        'auth.password_reset_form'         => \Snap\Admin\Ui\Auth\PasswordResetFormPage::class,
        'auth.forgot'                      => \Snap\Admin\Ui\Auth\ForgotPage::class,
        'me'                               => \Snap\Admin\Ui\Auth\MePage::class,
        'module.dashboard'                 => \Snap\Admin\Ui\Dashboard\DashboardPage::class,
        'module.search'                    => \Snap\Admin\Ui\Module\SearchResultsPage::class,
        'module.resource.index'            => \Snap\Admin\Ui\Module\Resource\IndexPage::class,
        'module.resource.table'            => \Snap\Admin\Ui\Module\Resource\TablePage::class,
        'module.resource.listing'          => \Snap\Admin\Ui\Module\Resource\ListingPage::class,
        'module.resource.grid'             => \Snap\Admin\Ui\Module\Resource\GridPage::class,
        'module.resource.calendar'         => \Snap\Admin\Ui\Module\Resource\CalendarPage::class,
        'module.resource.map'              => \Snap\Admin\Ui\Module\Resource\MapPage::class,
        'module.resource.create'           => \Snap\Admin\Ui\Module\Resource\CreatePage::class,
        'module.resource.create_inline'    => \Snap\Admin\Ui\Module\Resource\CreateInlinePage::class,
        'module.resource.edit'             => \Snap\Admin\Ui\Module\Resource\EditPage::class,
        'module.resource.edit_inline'      => \Snap\Admin\Ui\Module\Resource\EditInlinePage::class,
        'module.resource.show'             => \Snap\Admin\Ui\Module\Resource\ShowPage::class,
        'module.resource.show_inline'      => \Snap\Admin\Ui\Module\Resource\ShowInlinePage::class,
        'module.resource.delete'           => \Snap\Admin\Ui\Module\Resource\DeletePage::class,
        'module.resource.delete_inline'    => \Snap\Admin\Ui\Module\Resource\DeleteInlinePage::class,
        'module.resource.duplicate'        => \Snap\Admin\Ui\Module\Resource\DuplicatePage::class,
        'module.resource.duplicate_inline' => \Snap\Admin\Ui\Module\Resource\DuplicateInlinePage::class,
        'module.resource.input'            => \Snap\Admin\Ui\Module\Resource\InputPage::class,
        'module.resource.compare'          => \Snap\Admin\Ui\Module\Resource\ComparePage::class,
],
```

### Data Types ###
Data types are a way to automatically transform a `$data` property value. To implement, prefix the key name with the name of the data type and a `:`.
By default, the following data types are specified in the `config/snap/ui.php` file.
```
'data_types' => [
	''       => 'Snap\Ui\DataTypes\UiComponentType',
	'ui'     => 'Snap\Ui\DataTypes\UiComponentType',
	'object' => 'Snap\Ui\DataTypes\ObjectType',
	'config' => 'Snap\Ui\DataTypes\ConfigType',
	'view'   => 'Snap\Ui\DataTypes\ViewType',
],
```
* `UiComponentType`: Converts a class path to the actual `UIComponent` instance. `UIComponent` properties can be passed to these child components using the following format:
```
':buttons' => '\Snap\Admin\Ui\Components\FormButtonBar[module,resource]',
// OR
'ui:buttons' => '\Snap\Admin\Ui\Components\FormButtonBar[module,resource]',
```

* `ObjectType`: Converts a class path to the actual class instance specified in [Laravel's IOC container](https://laravel.com/docs/5.7/container). Similar to the `UiComponentType`, additional parameters can be pass using the following syntax where `resource` is a property on the `UIComponent`:
```
'object:menu' => '\Snap\Menu\MenuBuilder[resource]',
```

* `ConfigType`: Uses a configuration value:
```
'config:menu' => 'config:page_title' => 'snap.admin.title',
```

* `ViewType`: Converts the specified value to a view:
```
'view:menu' => 'admin::components.search' => 'snap.admin.title',
```


### Bindings ###
UI bindings are a way to alias mappings. This allows you to define data properties with the `ui:` prefix that references the alias like so:
```
protected $data = [
    'ui:dashboard' => 'module.dashboard'
];   
```

---

## Common UI Traits ##
* `Snap\Ui\Traits\VueTrait`
* `Snap\Ui\Traits\AttrsTrait`
* `Snap\Ui\Traits\CssClassesTrait`

---

## UI Facade ##
The admin module has an `UI` facade with the following methods: 
* `UI::make($name = null, $data = [], Closure $callback = null)`: 
* `UI::bind($name, $class = null)`: 
* `UI::isBound($name)`: 

---

## ui Helper Function ##
The `ui` helper function allows you to make a UI component.