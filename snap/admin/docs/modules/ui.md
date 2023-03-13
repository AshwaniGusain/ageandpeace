# <a id="traits" href="{docs_url}/admin/modules">Modules</a>: UI #

* [Pages](#Page)
	* [BasePage](#BasePage)
	* [Module Pages](#ModulePages)
	* [Resource Module Index Pages](#ResourceModuleIndexPages)
		* [TablePage](#TablePage)
		* [ListingPage](#ListingPage)
		* [CalendarPage](#CalendarPage)
		* [GridPage](#GridPage)
		* [MapPage](#MapPage)
	* [Resource Module Detail Pages](#ResourceModuleDetailPages)
		* [Create, Edit & Duplicate Pages](#FormPage)
		* [Delete Page](#DeletePage)
		* [Compare Page](#ComparePage)
* [Components](#Components)
	* [Resource Module Index Components](#ResourceModuleIndexComponents)
		* [Heading](#Heading)
		* [IndexButtonBar](#IndexButtonBar)
		* [IndexDropdown](#IndexDropdown)
		* [Filters](#Filters)
		* [Scopes](#Scopes)
		* [Search](#Search)
		* [DataTable](#DataTable)
		* [HierarchcialListing](#HierarchcialListing)
		* [CalendarList](#CalendarList)
		* [GridList](#GridList)
		* [MapList](#MapList)
	* [Resource Module Detail Components](#ResourceModuleDetailComponents)
		* [Form](#Form)
		* [FormButtonBar](#FormButtonBar)
		* [RelatedPanel](#RelatedPanel)



---
	
## <a name="Pages"></a>Pages ##
The admin displays it's page contents by leveraging SNAP's `UI` package and the `UIComponent` class. 
This provides an object structure with greater flexibility in manipulating the UI. Pages are generally
made up of data variables that are often nested `UIComponents` and have there own properties and methods 
for changing their behavior.

---

### <a name="BasePage"></a>BasePage ###
Admin pages should extend the `Snap\Admin\Ui\BasePage` which itself inherits from the `UIComponent`.

#### Default Data Variables ####
The `Snap\Admin\Ui\BasePage` will provide the default data variables that you can manipulate:
* `config:page_title`: The default `<title>` tag value. This data variable pulls it's value from the config path specifed (note the`config:` prefix that makes it a config data type). `snap.admin.title`,
* `config:admin_title`: The title that appears on the left above the menu. Similar to the default `page_title` variable, this pulls it's value from the config path specifed (note the`config:` prefix that makes it a config data type). `snap.admin.title`,
* `styles`: Additional CSS files to load on the page.
* `scripts`: Additional Javascript script to load on the page.            => [],
* `js_config`: SNAP javascript object configuration parameters.
```
[
	'el'          => '#snap-admin',
	'urlPaths'    => ['admin' => '/admin'],
	'environment' => 'testing',
	'debug'       => [
		'level' => 3,
		'clear' => false // need for Safari
	],
],
```
* `object:menu`: The left hand menu object Snap\Admin\Ui\LayoutMenu.
* `object:request`: The `Illuminate\Http\Request` object.
* `inline`: Determines whether to use the main admin template or the inline template which is used for modal display. Default is `false`.

#### Views ####
The `Snap\Admin\Ui\BasePage` uses the `snap\admin\resources\views\layouts\admin.blade.php` file by default but if the data variable of 
`inline` is set to `true` then it will use the `admin-inline.blade.php` file instead which does not include the menu and is meant for a smaller window size.

---

### Module Pages ###
Module objects have a `ui` property in which to map handles to UI components. 
The properties value should be a `key/value` array with the key being the 
handle and the value being either an alias to a ui object (which are configured 
in the `App\Admin\Providers\ModulesServiceProvider` (e.g. module.resource.table) 
or a UI class name. For example, the default ui handle mapping for a `Resource Module` 
is the following (note that it uses aliases as values instead of class names):
                                                 
 ```
  protected $ui = [
		 'table'            => 'module.resource.table',
		 'listing'          => 'module.resource.listing',
		 'map'              => 'module.resource.map',
		 'grid'             => 'module.resource.grid',
		 'calendar'         => 'module.resource.calendar',
		 'create'           => 'module.resource.create',
		 'create_inline'    => 'module.resource.create_inline',
		 'edit'             => 'module.resource.edit',
		 'edit_inline'      => 'module.resource.edit_inline',
		 'delete'           => 'module.resource.delete',
		 'delete_inline'    => 'module.resource.delete_inline',
		 'duplicate'        => 'module.resource.duplicate',
		 'duplicate_inline' => 'module.resource.duplicate_inline',
		 'input'            => 'module.resource.input',
		 'compare'          => 'module.resource.compare',
	 ];
 ```

The module has a corresponding `ui($handle)` method in which you can use in your 
module's custom controller like so:

```
public function table()
{
	return $this->module->ui('table', function ($ui) {

		/* Put your custom code table view code here:
		$ui->heading->setTitle('This is a new heading!');
		$ui->table
			->setColumns(['name', 'email'])
			->addIgnored('updated_at')
			->addAction('{id}/notify', 'NOTIFY')
			->addAction(function($values){
				return '<a href="'.$values['id'].'/deactivate">DEACTIVATE</a>';
			});
		*/
	})->render();
}
``` 

Although most module customization will be done through trait service object, you can specify 
more granular updates using the syntax of **`ui{Handle}($ui, Request $request)`** on your module class
as opposed to creating a custom controller method as specified in the example above. 
These methods would sit alongside your <a href="{docs_url}/admin/modules/traits#TableTrait">trait 
service object manipulation methods</a>. For example:

```
public function uiTable($ui, Request $request)
{
	$ui
		->setColumns(['name', 'email'])
		->addIgnored('updated_at')
		->addAction('{id}/notify', 'NOTIFY')
		->addAction(function($values){
			return '<a href="'.$values['id'].'/deactivate">DEACTIVATE</a>';
		});
}
```

---

### <a name="ResourceModuleIndexPages"></a>Resource Module Index Pages ### 
The Admin package comes with five different types of index pages for your resource. 
The default index page is the <a href="#TablePage">`TablePage`</a>. Depending on your module, 
you may want that to be different or provide multiple options. For example, an `EventModule` might
want the <a href="#CalendarPage">`CalendarPage`</a> view to display the events in addition to 
the <a href="#TablePage">`TablePage`</a>.

---

#### <a name="TablePage"></a>Table Page ####
The `Snap\Admin\Ui\Module\Resource\TablePage` is the table view for a resource module and is used by the `Snap\Admin\Modules\Traits\TableTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following
additional data variables.
* `ui:table`: Displays the nested `Snap\Admin\Ui\Components\DataTable` UI component which itself uses the `Snap\DataTable\DataTable` class for table rendering.
* `ui:filters`: Displays the filters input area and associated toggle button above the table area using the nested `Snap\Admin\Ui\Components\Filters` UI component if the `Snap\Admin\Modules\Traits\FiltersTrait` is implemented on the module.
* `ui:scopes`: Displays the tabs representing scoped queries above the table area usng the nested `Snap\Admin\Ui\Components\Scopes` UI component if the `Snap\Admin\Modules\Traits\ScopesTrait` is implemented on the module.
* `limit_options`: The dropdown select options for determining the pagination limit. Default value is `[50, 100, 200]`.
* `limit`: This is set buy the `Request` object's `limit` parameter that is sent from the page. Default value is null.

---

#### <a name="ListingPage"></a>Listing Page ####
The `Snap\Admin\Ui\Module\Resource\ListingPage` is the listing view for a resource module and is used by the `Snap\Admin\Modules\Traits\ListingTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following
additional data variables.

* `ui:listing`: Displays the nested `Snap\Admin\Ui\Components\HierarchicalListing` UI component which itself uses the `Snap\Menu\Menu` class for list rendering.
* `ui:filters`: Displays the filters input area and associated toggle button above the table area using the nested `Snap\Admin\Ui\Components\Filters` UI component if the `\Snap\Admin\Modules\Traits\FiltersTrait` is implemented on the module.
* `ui:scopes`: Displays the tabs representing scoped queries above the table area usng the nested `Snap\Admin\Ui\Components\Scopes` UI component if the `Snap\Admin\Modules\Traits\ScopesTrait` is implemented on the module.

---

#### <a name="CalendarPage"></a>Calendar Page ####
The `Snap\Admin\Ui\Module\Resource\CalendarPage` is the calendar view for a resource module and is used by the `Snap\Admin\Modules\Traits\CalendarTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following
additional data variables.

* `ui:calendar`: Displays the nested `Snap\Admin\Ui\Components\CalendarList` UI component which itself uses the `Snap\Admin\Ui\Components\CalendarList` class for displaying the calendar.
* `ui:filters`: Displays the filters input area and associated toggle button above the table area using the nested `Snap\Admin\Ui\Components\Filters` UI component if the `\Snap\Admin\Modules\Traits\FiltersTrait` is implemented on the module.
* `ui:scopes`: Displays the tabs representing scoped queries above the table area usng the nested `Snap\Admin\Ui\Components\Scopes` UI component if the `Snap\Admin\Modules\Traits\ScopesTrait` is implemented on the module.

---

#### <a name="GridPage"></a>Grid Page ####
The `Snap\Admin\Ui\Module\Resource\GridPage` is the grid view for a resource module and is used by the Resource Module `Snap\Admin\Modules\Traits\GridTrait`.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following
additional data variables.

* `ui:grid`: Displays the nested `Snap\Admin\Ui\Components\GridList` UI component which itself uses the `Snap\Admin\Ui\Components\GridList` class for displaying the grid items.
* `ui:filters`: Displays the filters input area and associated toggle button above the table area using the nested `Snap\Admin\Ui\Components\Filters` UI component if the `Snap\Admin\Modules\Traits\FiltersTrait` is implemented on the module.
* `ui:scopes`: Displays the tabs representing scoped queries above the table area usng the nested `Snap\Admin\Ui\Components\Scopes` UI component if the `Snap\Admin\Modules\Traits\ScopesTrait` is implemented on the module.
* `limit_options`: The dropdown select options for determining the pagination limit. Default value is `[50, 100, 200]`.
* `limit`: This is set buy the `Request` object's `limit` parameter that is sent from the page. Default value is null.

---

#### <a name="MapPage"></a>Map Page ####
The `Snap\Admin\Ui\Module\Resource\MapPage` is the map view for a resource module and is used by the `Snap\Admin\Modules\Traits\MapTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following
additional data variables.

* `ui:grid`: Displays the nested `Snap\Admin\Ui\Components\MapList` UI component which itself uses the `Snap\Admin\Ui\Components\MapList` class for displaying the map.
* `ui:filters`: Displays the filters input area and associated toggle button above the table area using the nested `Snap\Admin\Ui\Components\Filters` UI component if the `\Snap\Admin\Modules\Traits\FiltersTrait` is implemented on the module.
* `ui:scopes`: Displays the tabs representing scoped queries above the table area usng the nested `Snap\Admin\Ui\Components\Scopes` UI component if the `Snap\Admin\Modules\Traits\ScopesTrait` is implemented on the module.
* `limit_options`: The dropdown select options for determining the pagination limit. Default value is `[50, 100, 200]`.
* `limit`: This is set buy the `Request` object's `limit` parameter that is sent from the page. Default value is null.

---

### Resource Module Detail Pages ###
There are a number of resource module detail pages that may be accessed depending on the 
module traits. 

---

#### <a name="FormPage"></a>Create, Edit & Duplicate Pages ####
The `Snap\Admin\Ui\Module\Resource\CreatePage`, `Snap\Admin\Ui\Module\Resource\EditPage` and 
`Snap\Admin\Ui\Module\Resource\DuplicatePage` pages inherit from the `Snap\Admin\Ui\Module\Resource\FormPage` 
which provides the form view for creating a resource when using the `Snap\Admin\Modules\Traits\FormTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` these pages have the following additional data variables.

* `resource`: The resource to edit or duplicate. Not applicable to the `CreatPage`.
* `ui:heading`: Displays the nested `Snap\Admin\Ui\Components\Heading` UI component and is used to display the title, icon and back button (if necessary) of the page.
* `ui:buttons`: Displays the nested `Snap\Admin\Ui\Components\FormButtonBar` UI component and is the buttons displayed below the form area.
* `ui:alerts`: Displays the nested `Snap\Ui\Components\Bootstrap\Alerts` UI component and is used to display success and error messages on the page.
* `ui:form`: Displays the nested `Snap\Admin\Ui\Components\Form` UI component and is used to display the form.
* `ui:related_panel`: Displays the nested `Snap\Admin\Ui\Components\RelatedPanel` UI component and is used to display related resource information on the panel next to the form.
* `ui:preview`: Displays the nested `Snap\Admin\Ui\Components\Preview` UI component and is only displayed if the using the `Snap\Admin\Modules\Traits\PreviewTrait` Resource Module trait.
* `form_component`: The name of the Vue.js component that encapsulates the form. Default is `snap-form`.

---

#### <a name="DeletePage"></a>DeletePage ####
The `Snap\Admin\Ui\Module\Resource\DeletePage` also inherits from the `Snap\Admin\Ui\Module\Resource\FormPage` similar to the form-based pages and is used by the `Snap\Admin\Modules\Traits\DeletableTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following additional data variables.

* `ids`: The resource IDs to delete (yes, you delete more than one at a time).

---

#### <a name="ComparePage"></a>ComparePage ####
The `Snap\Admin\Ui\Module\Resource\ComparePage` allows you to compare the current resource version to those previously saved if the module uses the `Snap\Admin\Modules\Traits\RestorableTrait` Resource Module trait.
In addition to the data variables inherited from the `Snap\Admin\Ui\BasePage` the page has the following additional data variables.

* `heading`:     => '\Snap\Admin\Ui\Components\Heading[module]',
* `alerts`      => '\Snap\Ui\Components\Bootstrap\Alerts',
* `resource`    => null,
* `version`      => null,
* `restore_data => [],


##### Components #####
@TODO

### <a name="ResourceModuleIndexComponents"></a>Resource Module Index Components ###
@TODO

---

#### <a name="Heading"></a>Heading ####
@TODO

---

#### <a name="IndexButtonBar"></a>IndexButtonBar ####
@TODO

---

#### <a name="IndexDropdown"></a>IndexDropdown ####
@TODO

---

#### <a name="Heading"></a>Heading ####
@TODO

---

#### <a name="Filters"></a>Filters ####
@TODO

---

#### <a name="Scopes"></a>Scopes ####
@TODO

---

#### <a name="Search"></a>Search ####
@TODO

---

#### <a name="DataTable"></a>DataTable ####
@TODO

---

#### <a name="HierarchcialListing"></a>HierarchcialListing ####
@TODO

---

#### <a name="CalendarList"></a>CalendarList ####
@TODO

---

#### <a name="GridList"></a>GridList ####
@TODO

---

#### <a name="MapList"></a>MapList ####
@TODO

---

### <a name="ResourceModuleDetailComponents"></a>Resource Module Detail Components ###
@TODO

---

#### <a name="Form"></a>Form ####
@TODO

---

#### <a name="FormButtonBar"></a>FormButtonBar ####
@TODO

---

#### <a name="RelatedPanel"></a>RelatedPanel ####
@TODO


