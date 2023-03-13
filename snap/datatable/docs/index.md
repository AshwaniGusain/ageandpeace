# SNAP Data Table Package #

The SNAP Data Table package provides an easy method for adding sortable data tables to your projects:
`\Snap\DataTable\DataTable` is the primarily class for building data tables. Below is an example:

* [Example](#example)
* [Loading Data](#data)
* [Columns](#columns)
* [Headers](#headers)
* [Invisible Columns](#invisible)
* [Ignored Columns](#ignored)
* [Actions](#actions)
* [Formatters](#formatters)
* [Sorting](#sorting)
* [Styling](#styling)

---

## <a id="example"></a>Example ##
```
$droids = Droids::where('active', '1')->get();
$columns = ['name', 'price', 'cost', 'manufacturer', 'active', 'image', 'purchased_date', 'created_at'];

// Using static "make" method
$table = \Snap\DataTable\DataTable::make($droids, $columns);

// OR object and load method
$table = new \Snap\DataTable\DataTable();
$table->load($droids, $columns);

$table
	->addFormatter('money_format', ['price', 'cost'])
	->addFormatter('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
	->addFormatter(function($value, $data, $key){
		return '<img src="'.$value.'" alt="" height="80">';
	}, ['image'])
	->addFormatter('\Snap\DataTable\DataTableFormatters::dateFormatter', ['purchased_date'])
	->addAction(url('droids/edit/{id}'), 'Edit', ['class' => 'btn btn-sm']));
	->addIgnored('created_at') // Could just remove it from the $columns variable above too :)
	->getColumn('image')->setSortable(false);
	;

echo $table;
``` 

---

## <a id="data"></a>Loading Data ##
Data can be loaded in a couple ways as demonstrated in the example above:
```
$table = \Snap\DataTable\DataTable::make($data);
// OR
$table = new \Snap\DataTable\DataTable();
$table->load($data); 
```
The data table accepts the following data formats:
1. `array`
2. `\Illumination\Support\Collection`
3. `\Illuminate\Database\Query\Builder`
3. `JSON`
4. Oject that has a `getCollection` method on it (e.g. paginated data)

---

## <a id="columns"></a>Columns ##
Column display can be controlled in several of ways. As displayed in the example above, the second parameter of the `DataTable::make($data, $columns)` or the object `$table->load($data, $columns)` are probably the two most common ways.
```
$columns = ['name', 'price', 'cost', 'manufacturer', 'active', 'image', 'purchased_date', 'created_at'];
$table = \Snap\DataTable\DataTable::make($data, $columns);
// OR
$table = new \Snap\DataTable\DataTable();
$table->load($data, $columns); 
``` 
---

## Headers ##
By default, the column headings will be an `ucwords()` of the column with underscores being converted to spaces.
To customize the heading, you can set a key and a value for the array with the key being the column and the value being the heading:
``` 
$columns = ['name' => 'Droid Name'...];
```
---

## <a id="invisible"></a> Invisible Columns ##
Occasionally, you may need to have a column rendered but visually hidden. To do this, use the `setInvisible($columns)` method.
This Can be helpful if you want to have the ability to toggle columns on or off with javascript.

---

## <a id="ignored"></a> Ignored Columns ##
Data passed to the table can be ignored for rendering by using the `addIgnored($columns)` method.
This is often convenient for hiding `id` columns that may be needed for there data in the actions column (see next section).

---

## <a id="actions"></a> Actions ###
Each row of the table can have an `actions` column such as `edit`, `view` or `delete`. There are two ways you can set an action:
```
// URL, label, link attributes
$table->addAction('users/edit/{id}', 'Edit', ['class' => 'action-edit']);

// Closure
$table->addAction(function($values) {
   return '<a href="'.url('users/edit/'.$values['id']).'" class="action-edit">EDIT</a>';
});
```

---

## <a id="formatters"></a>Formatters ##
You can alter the displayed value by using the `addFormatter($formatter, $columns = null)` method. 
If no `$columns` parameter is passed or the string `*` the formatter function will run on all columns.
There are a number of built-in formatter functions on the `\Snap\DataTable\DataTableFormatters` class that can be used:
```
$table->addFormatter('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
$table->addFormatter(function($value, $data, $key){
                return '<img src="'.$value.'" alt="" height="80">';
            }, ['sm_image_url']);
$table->addFormatter('\Snap\DataTable\DataTableFormatters::truncateFormatter,50', ['description'])
$table->addFormatter('\Snap\Admin\Ui\Formatters\EditInline::format', ['name', 'description', 'active'])
```
The callback function automatically gets passed three parameters in this order:
* `$columnData`: The data for display to be manipulated
* `$rowData`: All the data for the row in an array
* `$key`: The key (column name)

Callbacks can be passed additional parameters by using a comma and then the parameter value:
* '\Snap\DataTable\DataTableFormatters::transFormatter,my_trans_key`
* '\Snap\DataTable\DataTableFormatters::truncateFormatter,50`
* '\Snap\DataTable\DataTableFormatters::containerFormatter,100,300`
* '\Snap\DataTable\DataTableFormatters::urlFormatter,1`
* '\Snap\DataTable\DataTableFormatters::emailFormatter,1`
 
---

## <a id="sorting"></a>Sorting ##
Sorting is handled via javascript. It loads the entire HTML though and replaces the contents giving you full power to leverage PHP for your rendering logic of the column data.

---

## <a id="styling"></a>Styling ##
There are a number of styling classes you can use to customize the HTML output.
```
$table->setClass(): Default is `table table-striped datatable`
$table->setFirstColumnClass($class): Default is `first`
$table->setLastColumnClass($class): Default is `last`
$table->setColumnSortClass($class): Default is `active`
$table->setSortableClass($class): Default is `sortable`
$table->setSortableClass($class): Default is `sortable`
$table->setColumnClass($class): Default is `table-col`
$table->setColumnAscClass($class): Default is `asc`
$table->setColumnDescClass($class): Default is `desc`
$table->setColumnClassPrefix($prefix): Default is `table-col-` and will append the column index at the add
```


