# <a id="traits" href="{docs_url}/admin/modules">Modules</a>: Traits #
Adding and customizing module functionality is done using [PHP traits](http://php.net/manual/en/language.oop5.traits.php). 
The SNAP Admin Package comes with many module traits out of the box for you.

##### Global Traits #####
The following trait can be used with both `Basic Modules` and `Resource Modules`:
* [NavigableTrait](#NavigableTrait): Adds navigation items to the admin menu (not specific to a `Resource Module`).

The following traits are specific to `Resource Modules` only:

##### Index Page Traits #####
* [TableTrait](#TableTrait): Adds a sortable table view with pagination.
* [ListingTrait](#ListingTrait): Adds a nestable list view that can be reordered (good for hierarchical resource models that have `parent_id` references).
* [GridTrait](#GridTrait): Adds a grid view (good for image centric resource modules).
* [CalendarTrait](#CalendarTrait): Adds a calendar view (good for date centric resource modules like events).
* [MapTrait](#MapTrait): Adds a map view with pagination (good for geo point centric modules).
* [SearchTrait](#SearchTrait): Adds the ability to filter a resource by entering in string value in the search box (uses an `OR` in the query's where conditions).
* [FiltersTrait](#FiltersTrait): Adds the ability to filter resource information with form inputs (uses an `AND` in the query's where conditions). For example, a dropdown select to filter the display of resources by status.
* [ScopesTrait](#ScopesTrait): Adds scoped data results resource selection (tables and map traits only). For example, you may have a `scopeActive()` method on your model. This will create a tab for displaying only active resources.
* [ExportableTrait](#ExportableTrait): Adds the ability to export filtered data into a CSV format from the index views dropdown list.

##### Resource Page Traits #####
* [FormTrait](#FormTrait): Adds both a create and edit form for your resource module's data.
* [ShowTrait](#ShowTrait): Adds an uneditable display only view of the data.
* [RelatedInfoTrait](#RelatedInfoTrait): Displays additional related information about a resource in a panel on the right.
* [DeletableTrait](#DeletableTrait): Adds the ability to delete a resource.
* [DuplicateTrait](#DuplicateTrait): Allows a resource to be duplicated. Custom duplication logic can be done by overriding the model's `replicate()` method.
* [LogTrait](#LogTrait): Logs changes to a resource in a `snap_logs` table.
* [PreviewTrait](#PreviewTrait): Allows you to preview changes to the resource and it's related web page (e.g. a news module with a slug field may have an associated page that uses a `slug` input.  You could preview the associated page at `/news/{slug}`)
* [RestorableTrait](#RestorableTrait): Allows you to restore from a previously saved resource. Data is saved into a `snap_archives` table.
* [OthersNavigationTrait](#OthersNavigationTrait): Adds a dropdown navigation to the related panel to the right of the form of other resource models to edit.

##### Misc. Page Traits #####
* [AjaxTrait](#AjaxTrait): Allows you to define methods on your module and make AJAX requests to those methods using the format of `admin/mymodule/ajax/{myModuleMethod}`.
* [IndexableTrait](#IndexableTrait): Indexes data into a `snap_search` table and allows it to be searched in the admin.

---

## Creating Traits ##
You can also easily create your own traits. A module trait has two special methods that can be added for initialization purposes. 
* `register{TraitName}()`: This method is used for creating an alias to the trait, registering any routes and binding more global services. This is called upon initialization of the module object.
* `boot{TraitName}()`: This method is called from within the module's controller initialization and often used to bind services.

### Services ###
A trait is usually accompanied with a `service` object. The `service` object provides the layer of customization between the trait and the admin.
A service object usually is used to do one or more of the following:
* Manipulate UI components (e.g. show/hide a preview button).
* Alter the module's query object (e.g. filter search results based on a request parameter).
* Alter the requests behavior (e.g. save index data to a search module).

Most traits provide service object manipulation methods on the module object that use the same name as the service. For example, The `FormTrait` adds a `form()`
method to the module object to further manipulate the module's form. Below are some examples:
```
public function form($form, Request $request = null, $resource = null)
{
	$form
		->scaffold()
		->inputs(
		[
			Slug::make('slug')
		]
	);
}

public function filters($filters, Request $request)
{
	$filters->add(Filter::make('name', 'where', '=')->withInput('text'));
}

public function table($table, Request $request)
{
	$table
		->defaultSort('-slug')
		->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
	;
}
```

A module has several methods for services:
* `bindService($name, $service)`: Binds the service object to the module. Closures can be used for lazying instantiation of the service object.
* `service($name)`: Returns the bound service object based on the past in service name.
* `hasService($name)`: Returns a boolean value based on whether a service object exists on the module.

Depending on the trait, service objects can be bound with the `bindService()` method in either the `register{TraitName}()` or `boot{TraitName}()`. 
Global type services like the `NavigableTrait` are bound with the `bindService()` method in the `register{TraitName}()` method whereas more page 
specific traits like `FiltersTrait` are bound in the `boot{TraitName}()` method.

---

## <a name="NavigableTrait"></a>NavigableTrait (navigable) ##
The `\Snap\Admin\Modules\Traits\NavigableTrait` is used for displaying a menu item in the navigation and is not resource module specific.
* Alias\Service: `navigable`

##### Routes #####
None.

##### Module Properties #####
The following additional properties can be added to your module: 
* `$menuLabel`: The text you want to use for the menu label in the admin.
* `$menuParent`: The parent modules `fullHandle()` key that this module's menu item should belong to (default is `null`).

##### Module Methods #####
The following methods are added to the module:
* `menuLabel()`: The text you want to use for the menu label in the admin.
* `menuParent()`: The parent modules `fullHandle()` key that this module's menu item should belong to (default is `null`).
* `setMenuParent()`: A convenience method to change the parent menu item the module should belong to at run-time.
* `setMenuLabel()`: A convenience method to change the menu label to the module should belong to at run-time.

##### Service Methods #####
The associated service object has the following method:
* `menu($menu, $parent = null)`: Adds the module's menu items to the main admin menu object and returns the admins module object.

##### UI Components ######
* `\Snap\Admin\Ui\Layout\Menu`: The menu in the admin on the left.

---

## <a name="TableTrait"></a>TableTrait  (table) ###
The `\Snap\Admin\Modules\Traits\TableTrait` is usually the main index view for a resource module and displays a paginated table.

* Alias\Service: `table`

##### Routes #####
* table (GET)

##### Module Properties #####
None.

##### Module Methods #####
* `table(TablesService $tables, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `columns($columns)`: The columns to display.
* `defaultSort($sort)`: The default column and direction (prefix with a `-` for descending order).
* `sortable($sortableColumns)`: The columns that are sortable. Default is all columns.
* `format($formatter, $columns = null)`: Formatters to be applied to column data (e.g. converting a boolean 1/0 value to `yes` or `no`).
* `actions($actions)`: The row actions to display on the table. The default value is `['edit' => 'form', 'show' => 'view', 'delete' => 'delete']`. 
The keys are the name of the action (which is associated with an icon) and the value is the alias name of the trait in which to determine
* `limit($limit)`: The number of records to paginate by. 
* `query($callback)`: Allows for further manipulation of the query used to retrieve the table's results.
* `pagination($callback)`: The `TableService` contains a `PaginationService` object. This method allows you to manipulate that object through a `Closure` function

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\TablePage`: A sortable table on the module's index page.

###### Example ######
```
public function table(TablesService $table, Request $request)
{
	$table
		->columns(['name', 'slug', 'active'])
		->defaultSort('-created_at')
		->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
		->actions(['edit' => 'form'])
		->limit(100)
		->query(function($query){
			$query->where('active', 1);
		})
		->pagination(function($pagination) {
			$pagination->limits(100, 200, 300])
		})
		;
	;
}
```

---

## <a name="ListingTrait"></a>ListingTrait (listing) ##
The `\Snap\Admin\Modules\Traits\ListingTrait` is used to display hierarchical listing information (e.g. taxonomy structures) on a module's index page.

* Alias\Service: `listing`

##### Routes #####
* listing (GET)
* sort (POST)

##### Module Properties #####
None.

##### Module Methods #####
* `listing(ListingService $search, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `itemTemplate($callback)`: The output for a single listing item. Value should be a closure that accepts the parameters `$item`, `$id` and `$level`.
* `sortable($sortable)`: Specifies whether the list is can be rearranged.
* `nestingDepth($nestingDepth)`: Specifies the nesting depth when sortable. Default is 3.
* `idColumn($column)`: The id column name used for building the parent child hierarchy. Default is the model's primary key (usually `id`).
* `parentColumn($column)`: The parent column name to be used to build the hierarchical relationship. Default is `parent_id`.
* `precedenceColumn($column)`: The precedence (ordering) column name. Default is `precedence`.
* `rootValue($value)`: The parent column's value that determines whether it is a root item. Default is `null`.
* `query($callback)`: Allows for further manipulation of the query used to retrieve the listing results.
* `items($items)`: Used to manually set the collection of items to be displayed and will override whatever is set to be return by the query.
 
##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\ListingPage`: A hierarchical list view on the module's index view.

###### Example ######
```
public function listing(ListingService $search, Request $request)
{
    $listing->itemTemplate(function($item, $id, $level){
        return view('my-item-view', ['item' => $item]);
    })
    ->sortable(true)
    ->nestingDepth(1)
    ->parentColumn('parent')
    ->rootValue(0)
    ;
}
```

---

## <a name="GridTrait"></a>GridTrait (grid) ##
The `\Snap\Admin\Modules\Traits\GridTrait` is used to display information in a grid block format and is specifically good for modules that use images as their main identifying property.

* Alias\Service: `grid`

##### Routes #####
* grid (GET)

##### Module Properties #####
None.

##### Module Methods #####
* `grid(GridService $grid, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `itemTemplate($callback)`: Specifies the output for a single grid item. Value should be a closure that accepts the parameter `$item`.
* `cols($cols)`: The number of columns to use for the display grid. Default is `4`.
* `limit($limit)`: The number of records to paginate by. 
* `query($callback)`: Allows for further manipulation of the query used to retrieve the grid results.

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\GridPage`: A grid view on the module's index view.

###### Example ######
```
public function grid(ListingService $grid, Request $request)
{
    $grid
    	->cols(2)
        ->itemTemplate(function($item, $id, $level){
        	return view('my-item-view', ['item' => $item]);
    	})
    ;
}
```

---

## <a name="CalendarTrait"></a>CalendarTrait (calendar) ##
The `\Snap\Admin\Modules\Traits\CalendarTrait` is used to display information in a calendar format and is specifically good for modules that have start and end dates or publish dates (e.g. events or blog posts).
* Alias\Service: `calendar`

##### Routes #####
* calendar (GET)

##### Module Properties #####
None.

##### Module Methods #####
* `calendar(CalendarService $grid, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `itemTemplate($callback)`: Specifies the output for a single grid item. Value should be a closure that accepts the parameter `$item`.
* `cols($cols)`: The number of columns to use for the display grid. Default is `4`.
* `limit($limit)`: The number of records to paginate by. 
* `query($callback)`: Allows for further manipulation of the query used to retrieve the grid results.

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\CalendarPage`: The grid view on the module's index view.

###### Example ######
```
public function calendar(CalendarService $calendar, Request $request)
{
    $calendar
    	->startDateColumn('start_date')
        ->endDateColumn('end_date')
    ;
}
```

---

## <a name="MapTrait"></a>MapTrait (map) ##
The `\Snap\Admin\Modules\Traits\MapTrait` is used to display information in a map format and is specifically good for modules that have geo location data fields (e.g. places of interest).
* Alias\Service: `map`

##### Routes #####
* map (GET)

##### Module Properties #####
None.

##### Module Methods #####
* `map(MapService $map, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `startingCoords($latitude, $longitude)`: Specifies the output for a single grid item. Value should be a closure that accepts the parameter `$item`.
* `zoom($zoom)`: The starting zoom level for the map.
* `limit($limit)`: The pagination limit to use for the display results.
* `url($url)`: The Open Street Map tiling URL. Default is`http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`.
* `options(array $options)`: Additional map options. Default is `['attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>']`.
* `getPointColumn($column)`: The name of the model's field to use for geo location. Default is `geo_point`.

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\MapPage`: A map view on the module's index view.

###### Example ######
```
public function map(ListingService $map, Request $request)
{
	$map
		->startingCoords(45.5122, 122.6587)
		->zoom(10)
		->geoPointColumn('points')
	;
}
```

---

## <a name="SearchTrait"></a>SearchTrait (search) ###
The `\Snap\Admin\Modules\Traits\SearchTrait` creates a search input on the module's index page which will filter the index's displayed results based on the input. 

* Alias\Service: `scopes`

##### Routes #####
None.

##### Module Properties #####
None.

##### Module Methods #####
* `search(SearchService $search, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `columns($columns)`: Sets which columns the search query should filter on.
* `query($term = null, $columns = [])`: Runs the search query on the model for a specified term.
* `isSearched()`: Returns a boolean value if the search filter has been applied to the query.

##### UI Components ######
* `\Snap\Admin\Ui\Components\Search`: A search input on the module's index pages.

###### Example ######
```
public function search(SearchService $search, Request $request)
{
	$search->columns(['name', 'slug']);
}
```

---

## <a name="FiltersTrait"></a>FiltersTrait  (filters) ###
The `\Snap\Admin\Modules\Traits\FiltersTrait` is used to create filtering inputs that affect a module's query.
A filter button toggle will appear on the index page above the table that can be used to display the filtering inputs. 

* Alias\Service: `filters`

##### Routes #####
None.

##### Module Properties #####
None.

##### Module Methods #####
* `filters(FiltersService $filters, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `add($key, $method = 'where', $operator = '=', $input = null)`: Adds filtering criteria to the module's query object.
The method can alternatively accept a `Snap\Admin\Modules\Traits\Filters\Filter` object instance which will allow you
to specify the type of input.
* `isFiltered()`: Returns a boolean value if any filters have been applied to the query. 

##### UI Components ######
* `\Snap\Admin\Ui\Components\Filters`: A expandable/collapsible area above the main area of the index that displays filterable inputs.

###### Example ######
```
public function filters(FiltersService $filters, Request $request)
{
	$filters
		->add('name', 'where', '=')
		
		// Using the Snap\Admin\Modules\Traits\Filters\Filter object so you can specify the type of input
		->add(Filter::make('name', 'where', '=')->withInput('text'))
		;
}
```

---

## <a name="ScopesTrait"></a>ScopesTrait (scopes) ##
The `\Snap\Admin\Modules\Traits\ScopesTrait` is used to create scoped query results.
Tabs appear over the table area and clicking the tab will display the results.
For example, you may want to see all active users. 

* Alias\Service: `scopes`

##### Routes #####
None.

##### Module Properties #####
* `$scaffoldScopes`: Determines whether to automatically call the `scaffold()` method on the service.

##### Module Methods #####
* `scopes(ScopesService $scopes, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `scaffold()`: Generates scopes based on the model.
* `add($method, $label = null)`: Accepts a `$key => $val` array with the `$key` being the scope method name on the model and the `$val` being the label displayed in the admin 
OR you can make multiple `add($method, $label)` method calls.

##### UI Components ######
* `\Snap\Admin\Ui\Components\Scopes`: A tabbed area above the main area of the module's index view and right below any filters (if the `FiltersTrait` is used).

###### Example ######
```
public function scopes(ScopesService $scopes, Request $request)
{
	$scopes
		->scaffold()
		->add('active', 'Active')
		->add(['inactive' => 'Inactive'])
		;
}
```
---

## <a name="FormTrait"></a>FormTrait (form) ##
The `\Snap\Admin\Modules\Traits\FormTrait` is used for displaying the form fields for the resource.
* Alias\Service: `form`

##### Routes #####
* create (GET)
* create_inline (GET)
* insert (POST)
* {resource}/edit (GET)
* {resource}/edit_inline (GET)
* {resource}/update (PUT PATCH)
* {resource}/upload (POST)
* input/{input}/{resource?} (GET)
* ajax/{method} (GET)

##### Module Properties #####
* `$scaffoldForm`: Determines whether the form should be derived from the resource object automatically.

##### Module Methods #####
* `form(FormService $form, Request $request)`: Service object manipulation method.
* `inputs()`: This is a convenience method since a lot of code usually goes into creating forms on a module. 
The method should return an array of form input objects (which will automatically be called by the service object's `inputs($inputs)` method).

##### Service Methods #####
* `scaffold($inputs = [], $hints = [])`: Will automatically generate the form based on the resource model.
The `$inputs` parameter limits returned inputs to just those names specified in the array.
The `$hints` parameter adds additional mapping hints for how the model maps inputs from its fields.
* `inputs()`: Adds inputs to the form.
* `rules($rules, $messages = [])`: Adds validation rules to the form.

**Methods not existing on the service will be proxied automatically to the `\Snap\Form\Form` object used to render the page.**

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\FormPage`: The form page used for creating and editing a model resource.
* `\Snap\Admin\Ui\Module\Resource\CreatePage`: By default, this is the same as the `FormPage` without a resource value. 
* `\Snap\Admin\Ui\Module\Resource\EditPage`: By default, this is the same as the `FormPage` with a resource value.

###### Examples ######
```
// Note the $resource parameter passed unlike other service object manipulation methods.
public function form(FormService $form, Request $request, $resource)
{
	$form
		->scaffold()
		->inputs(
			[
				Slug::make('slug')
			]
		)
		->rules(['slug' => 'required'])
		;
}

// As a convenience, you can specify inputs this way as a method on
// your module when you don't need the entire FormService object.
public function inputs()
{
    return [
    	Text::make('name'),
    	Slug::make('handle'),
    	Boolean::make('active'),
    ];
}
```

---

## <a name="ShowTrait"></a>ShowTrait (show) ##
The `\Snap\Admin\Modules\Traits\ShowTrait` displays an uneditable view of the resource data based on the form inputs.
* Alias\Service: `show`

##### Routes #####
* {resource} (GET): maps to the `Snap\Admin\Http\Controllers\ModuleResourceContorller::show($resource)`.

##### Module Properties #####
* None.

##### Module Methods #####
* `show(ShowService $form, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `scaffold($inputs = [], $hints = [])`: Will automatically generate the resource's data.
The `$inputs` parameter limits returned inputs to just those names specified in the array.
The `$hints` parameter adds additional mapping hints for how the model maps inputs from its fields.
* `inputs()`: Adds inputs that get displayed in `displayOnly` mode.

**Methods not existing on the service will be proxied automatically to the `\Snap\Form\Form` object used to render the page.**

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\ShowPage`: The page to display read only information of a resource.

###### Examples ######
```
public function show(ShowService $show, Request $request)
{
	$form
		->scaffold()
		->inputs(
			[
				Slug::make('slug')
			]
		)
		;
}

// As a convenience, you can specify inputs this way as a method on
// your module when you don't need the entire FormService object.
public function inputs()
{
    return [
    	Text::make('name')->displayAs(function($value, $input){
    		return 'The input name is:'.$value.' with an ID of:'.$input->id;
    	}),
    ];
}
```

---

## <a name="RelatedInfoTrait"></a>RelatedInfoTrait (relatedInfo) ##
The `\Snap\Admin\Modules\Traits\RelatedInfoTrait` displays additional related information about a resource in a panel on the right.
* Alias\Service: `relatedInfo`

##### Routes #####
* None.

##### Module Properties #####
* None.

##### Module Methods #####
* `relatedInfo(RelatedInfoService $relatedInfo, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `add($item)`: Will automatically generate the resource's data.

##### UI Components ######
* `\Snap\Admin\Ui\Components\RelatedPanel`: The panel that exists on the right of the form.

###### Examples ######
```
public function relatedInfo(RelatedInfoService $relatedInfo, Request $request)
{
	$relatedInfo
		->scaffold()
		->inputs(
			[
				Slug::make('slug')
			]
		)
		;
}

// As a convenience, you can specify inputs this way as a method on
// your module when you don't need the entire FormService object.
public function inputs()
{
    return [
    	Text::make('name')->displayAs(function($value, $input){
    		return 'The input name is:'.$value.' with an ID of:'.$input->id;
    	}),
    ];
}
```
---

## <a name="DeletableTrait"></a>DeletableTrait (deletable) ##
The `\Snap\Admin\Modules\Traits\DeletableTrait` adds the ability to delete a resource and provides an option in the index view's dropdown select for applicable views.
* Alias\Service: `deletable`

##### Routes #####
* {ids}/delete (GET)
* {ids}/delete_inline (GET)
* {ids}/delete (DELETE): : maps to the `Snap\Admin\Http\Controllers\ModuleResourceContorller::doDelete()`.

##### Module Properties #####
* `$allowForceDelete`: A module convenience property that will automatically call the `allowForceDelete()` on the `DeletableService` object.

##### Module Methods #####
* `deletable(DeletableService $delete, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `allowForceDelete($allow)`: A method to change whether to allow a model with the `Illuminate\Database\Eloquent\SoftDeletes` trait can force delete.

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\DeletePage`: The confirmation page that allows you to delete one or more resource items.

###### Examples ######
```
public function delete(DeletableService $delete, Request $request)
{
	$delete
		->allowForceDelete(true)
		;
}
```

---

## <a name="DuplicateTrait"></a>DuplicateTrait (deletable) ##
The `\Snap\Admin\Modules\Traits\DuplicateTrait` adds the ability to duplicate a resource and adds a button in the resource edit pages button bar.
* Alias\Service: `duplicate`

##### Routes #####
* {ids}/duplicate (GET)
* {ids}/duplicate_inline (GET)

##### Module Properties #####
* None.

##### Module Methods #####
* `duplicate(DuplicateService $duplicate, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `allowForceDelete($allow)`: A method to change whether to allow a model with the `Illuminate\Database\Eloquent\SoftDeletes` trait can force delete.

##### UI Components ######
* `\Snap\Admin\Ui\Module\Resource\DuplicatePage`: The duplication page page which is similar to a create page but with the values pre-filled.

---

## <a name="ExportableTrait"></a>ExportableTrait (export) ##
The `\Snap\Admin\Modules\Traits\ExportableTrait` adds the ability to export filtered data into a CSV format from the index views dropdown list.
* Alias\Service: `export`

##### Routes #####
* export (GET)

##### Module Properties #####
* None.

##### Module Methods #####
* `export(ExportableService $delete, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `fileName($fileName)`: Will change the downloaded CSV's filename. The default is the {pluralModuleName}-{YYYY-mm-dd}.csv'.
* `columns([$columns])`: Determines what columns to export to the CSV.
* `format($formatter, $columns = null)`: Formatters to be applied to column data (e.g. converting a boolean 1/0 value to `yes` or `no`).
* `getData()`: Returns the data in an array format.
* `download()`: Downloads the CSV with the appropriate HTTP request headers.

##### UI Components ######
* None.

###### Examples ######
```
public function export(ExportableService $export, Request $request)
{
	$export
		->fileName('download.csv')
		->columns('name', 'active')
		->format(function($val, $data){
			return ($val) == 1 ? 'Yes' : 'No';
		}, ['active']);
}
```

---

## <a name="LogTrait"></a>LogTrait (log) ##
The `\Snap\Admin\Modules\Traits\LogTrait` logs changes to a resource in a `snap_logs` table and can be viewed from the `Log Module`.
* Alias\Service: `log`

##### Routes #####
* None.

##### Module Properties #####
* None.

##### Module Methods #####
* `log(LogService $log, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `message($message)`: Will change the downloaded CSV's filename. The default is the {pluralModuleName}-{YYYY-mm-dd}.csv'.
* `level($level)`: Determines what columns to export to the CSV.
* `enable($enabled)`: Formatters to be applied to column data (e.g. converting a boolean 1/0 value to `yes` or `no`).
* `save($resource)`: Returns the data in an array format.

##### UI Components ######
* None.

###### Examples ######
```
public function log(LogService $log, Request $request)
{
	$log
		->enable(true)
		->message(function($resource){
			return 'The resource '.$resource->display_name.' successfully saved';
		})
		->level('info')
}
```
---

## <a name="PreviewTrait"></a>PreviewTrait (preview) ##
The `\Snap\Admin\Modules\Traits\PreviewTrait` allows you to preview changes to the resource and it's related web page (e.g. a news module with a slug field may have an associated page that uses a `slug` input.  You could preview the associated page at `/news/{slug}`)
* Alias\Service: `preview`

##### Routes #####
* None.

##### Module Properties #####
* None.

##### Module Methods #####
* `preview(PreviewService $preview, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `slugInput($slug)`: The slug is the URI segment that represents the unique resource as a page. The default is `slug`.
* `prefix($prefix)`: The part of the URL path that precedes the slug.

##### UI Components ######
* `Snap\Admin\Ui\Components\Preview`: The iframe that displays the content of the page you are previewing.

###### Examples ######
```
public function preview(PreviewService $preview, Request $request)
{
	$preview
		->slugInput('permalink')
		->prefix('news')
		;

}
```

---

## <a name="RestorableTrait"></a>RestorableTrait (restorable) ##
The `\Snap\Admin\Modules\Traits\RestorableTrait` allows you to restore from a previously saved resource. Data is saved into a `snap_archives` table.

* Alias\Service: `restorable`

##### Dependencies #####
* `RelatedInfoTrait`
* This trait requires the `Snap\Database\Model\Traits\RestorableTrait` to be set on the model.

##### Routes #####
* {resource}/compare/{version} (GET)
* {resource}/restore (POST)

##### Module Properties #####
* None.

##### Module Methods #####
* `restore(RestorableService $restore, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `max($max)`: The maximum number of records to log before truncating older records.

##### UI Components ######
* `Snap\Admin\Ui\Components\RestoreVersions`: Displays a dropdown in the `Related Info` panel to select an older archive version to restore to.

###### Examples ######
```
public function restore(RestorableService $restore, Request $request)
{
	$restore
		->max(5)
		->prefix('news')
		;
}
```

---

## <a name="OthersNavigationTrait"></a>OthersNavigationTrait (others) ##
The `\Snap\Admin\Modules\Traits\OthersNavigationTrait` Adds a dropdown navigation to the related panel to the right of the form of other resource models to edit.

* Alias\Service: `others`

##### Dependencies #####
* `RelatedInfoTrait`

##### Routes #####
* None.

##### Module Properties #####
* None.

##### Module Methods #####
* `others(OthersService $others, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `nameField($name)`: The name of the field to be used for the dropdown label. The default will be the model's `display_name` attribute.
* `valueField($value)`: The name of the field to be used for the dropdown value. The default is model's primary key (e.g. `id`).
* `query($callback)`: A Closure callback to the query used to generate the dropdown data. 

##### UI Components ######
* `Snap\Admin\Ui\Components\OthersNavigation`: Displays a dropdown in the `Related Info` panel to select another model to edit.

###### Examples ######
```
public function others(OthersService $others, Request $request)
{
	$others
		->nameField('title')
		->valueField('model_id')
		->query(function($query){
			$query->where('active', '=', 'yes');
		});
		;
}
```


---

## <a name="AjaxTrait"></a>AjaxTrait (ajax) ##
The `\Snap\Admin\Modules\Traits\AjaxTrait` allows you to define methods on your module and make AJAX requests to those methods using the format of `admin/mymodule/ajax/{myModuleMethod}`.

* Alias\Service: `ajax`

##### Routes #####
* ajax/{method} (GET)

##### Module Properties #####
* None.

##### Module Methods #####
* `ajax(AjaxService $ajax, Request $request)`: Service object manipulation method.
* `ajaxOptions()`: Returns `key/value` array that can be used for AJAXing in data for dropdown selects at the `admin/mymodule/ajax/options`.

##### Service Methods #####
* `whitelist($methods)`: Whitelists methods that can be automatically mapped from the module to the controller.
By default, it will allow any module methods that start with `ajax` (e.g. `ajaxOptions()`).
* `isWhitelisted($method)`: Returns a boolean value as to whether a method is whitelisted or not.

##### UI Components ######
* None.

###### Examples ######
```
public function restore(AjaxService $ajax, Request $request)
{
	$ajax
		->whitelist(['myOptions'])
		;
}
```

---

## <a name="IndexableTrait"></a>IndexableTrait (indexable) ##
The `\Snap\Admin\Modules\Traits\IndexableTrait` indexes data into a `snap_search` table and allows it to be searched in the admin.

* Alias\Service: `indexable`

##### Routes #####
* None.

##### Module Properties #####
* None.

##### Module Methods #####
* `indexable(IndexableService $indexable, Request $request)`: Service object manipulation method.

##### Service Methods #####
* `fields($fields)`: The model/table fields to be indexed. By default all attributes on the model are indexed except for
`getKeyName`, `getCreatedAtColumn`, `getUpdatedAtColumn`, `getDeletedAtColumn`, `getLastUpdatedByColumn` and `getUpdatedByColumn`. 
* `category($uri)`: The category to associate the indexed data with.
* `title($uri)`: The title to be associated with the indexed data and appears in the search results page.
* `excerpt($uri)`: The excerpt to be associated with the indexed data and appears in the search results page. 

##### UI Components ######
* `\Snap\Admin\Ui\Module\SearchResultsPage`: The page that displays the indexed search results.

###### Examples ######
```
public function indexable(IndexableService $indexable, Request $request)
{
	$indexable
		->fields(['name', 'content'])
		->uri(function($resource){
			return 'news/'.$resource->slug;
		})
		->title(function($resource){
			return $resource->name;
		})
		->exerpt(function($resource){
			return substr(strip_tags($resource->content), 0, 100);
		})
		->title(function($resource){
			return $resource->name;
		})
		;
}
