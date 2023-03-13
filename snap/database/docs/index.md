# SNAP Database Package #

The SNAP Database package augments the Eloquent data model with model traits and provides some additional database utility functions:

* [Config](#config)
* [Snap Model](#model)
	* [Model Validation](#validation)
	* [Class Casts](#casts)
	* [Model Sanitization](#sanitization)
	* [Generic Relationships](#relationships)
* [Additional Model Traits](#traits)
* [Model Macros](#macros)
* [DB Helpers & Utils](#utils)
---

## <a id="config"></a>Config ##
There is a `config/snap/db.php` file with a single configuration parameter to turn on database logging which is helpful for debugging purposes (e.g. the `debug_query()` function that comes with this package).
```
<?php 

return [
    'log_queries' => env('APP_DEBUG'),
]; 
```

If it is not found there, be sure to publish the `Snap\Database\DatabaseServiceProvider`.
```
php artisan vendor:publish --provider="Snap\Database\DatabaseServiceProvider"
```
---
## <a id="model"></a>Snap Model ##
The `Snap\Database\Model` extends Laravel's `Illuminate\Database\Eloquent\Model` model with some additional functionality including model validation on save, custom cast attributes, and auto sanitizing of data before save.

### <a id="validation"></a>Model Validation ###
Eloquent models by default don't provide built-in validation before saving. Instead, validation is normally expected to be in the Controllers and/or custom <a href="https://laravel.com/docs/5.8/requests" target="_blank">Request</a> object.
However, model saving can happen outside of Controllers and Requests such as in <a href="https://laravel.com/docs/5.8/seeding" target="_blank">Database Seeding</a> and often there are rules you want on the model that prevent it from saving if there is missing or malformed data. 
This is different then say situational rules where it's not necessary for saving the model but the business logic requires it. 
For example, take a situation where there is both a front end form to fill out client information and a back end SNAP module for admins to manage or manually add that information.
The front end form may have stricter rules such as required fields for `name` and `address`, `city`, `state`, `zip`, whereas the back end SNAP module may just need the `name`. 
In this case the `name` should be considered a model rule. Setting up model rules looks like the following:

```
use Snap\Admin\Modules\ModuleModel as Model;

class Client extends Model
{
    ....
    
    public static $rules = [
        'name' => 'required',
    ];
```

For multiple rules, use a pipe `|` as described in the <a href="https://laravel.com/docs/5.8/validation" target="_blank">Laravel Validation documentation</a>.

#### Auto Validation #### 
By default, Snap Model will automatically validate data on save based on the field type. 
Specific validations include:
* `enum`: Value must be one of the options specified in the database schema.
* `date`, `datetime` and `timestamp`: Value must be of valid format of `Y-m-d` in PHP format or `yyyy-mm-dd`.
* `year`: All values are digits.
* `length`: All length properties (e.g. varchar(100)), will make sure their size is not greater than the specified in the database schema.

There are additional properties that can be added to the model to further customize the autovalidation:
* `autoValidate`: A boolean value that will determine wether to automatically run validation on save. If the data doesn't validate, the data won't save and will return `false`.
* `autoValidateAttributes`: An array of attributes in which to auto validate. Default is all fields.

### <a id="casts"></a>Class Casts ###
Laravel provides a `cast` property which allows you to automatically cast data from the model into certain data types. This is often convenient for casting dates into `Carbon\Carbon` classes but is useful for `float`, `boolean` `json` data types.
The Snap Model adds the additional ability to cast to a specific custom class of your choosing. For example, perhaps, you have a `UrlHtml` class that you'd like to map a field to which returns a UrlHelper object that includes the ability to make a link or return the non-www version of the url.


### <a id="relationships"></a>Generic Relationships ###
The Snap Model provides an easy way to create a `BelongsToMany` relationship without the need for creating additional pivot tables but instead using a generic pivot table (see the snap_relationships table).
To use it, use the `sharedRelationship($model, $context = null, $tableInfo = null)` or
`sharedForeignRelationship($model, $tableInfo = null)` methods.
```
public function tasks()
{
	return $this->sharedRelationship(Task::class);
}
```
The opposite relationship is `sharedForeignRelationship($model)`.

### <a id="sanitization"></a>Model Sanitization ###
The Snap Model provides the ability to automatically sanitize data before saving. 
To incorporate this functionality add a `$sanitization` array property to your model class with the key being the field name and the value being a callable function (or an array that has the first value being a class instance and the second value being the method name on that instance to call). Below is an example: 

```
protected $sanitization = [
	'body' => 'clean_html',
];
```
You can use `*` to run the function on all the fields. Also, to call multiple functions, you can use the same format as for validation and usa a pipe (e.g. `|`) to separate functions.
```
protected $sanitization = [
	'*' => 'clean_html|strip_images',
];
```

The `snap/support` package provides a some helpful sanitization functions including:
* `sanitize_trim`: Will automatically trim any whitespace from the values (the Kernel middleware `\App\Http\Middleware\TrimStrings` already does this if it is set)
* `clean_html`: Uses the `HTMLPurifier` class to clean a string value. Use the `config/purifier.php` for additional configuration.
* `strip_javascript`: Strips javascript tags from a string.
* `strip_images`: Strips image tags from a string.
* `clean_numbers`: Removes numbers from a string.
* `safe_htmlentities`: Converts special character non HTML tag values to their entities.
* `clean_filename`: Cleans a file name and removes any invisible characters or `../` in the file name.
* `remove_invisible_characters`: Removes non displayable characters.
* `convert_utf8`: Converts a string to UTF-8 encoding.
* `escape_html`: Escapes HTML in a string.
* `escape_js`: Escapes javascript in a string.
* `escape_css`: Escapes CSS in a string.
* `escape_url`: Escapes a URL in a string.
* `escape_attr`: Escapes an HTML attribute in a string.
* `xss_clean`: Strips characters that can cause cross-site scripting attacks.

---

## <a id="traits"></a>Model Traits ##
* `Snap\Database\Model\Traits\FormatsDate`: Useful for dealing with displaying date timezones. More info can be found at <a href="https://www.qcode.in/managing-users-timezone-in-laravel-app/" target="_blank">this url</a>. 
* `Snap\Database\Model\Traits\HasActive`: Used for models with an `active` flag field. 
* `Snap\Database\Model\Traits\HasCoordinates`: Used for GeoSpatial fields (latitude and longitude).
* `Snap\Database\Model\Traits\HasDateRange`: Used for models that have a `start_date` and `end_date` fields and provides some additional convenience model methods.
* `Snap\Database\Model\Traits\HasDisplayName`: The Snap Model usually requires a friendly (non ID number) way to identify a record.
The Snap Model provides an additional property of `displayNameField` that can reference an existing field (e.g. `name`, `title`, or `label`). Used in the Resource Model `Snap\Admin\Modules\ModuleModel`.
Or, if the a method of `getDisplayNameAttribute()` can be added to your model. 
* `Snap\Database\Model\Traits\HasExpirationDate`: Used for models, that have expiration dates and by default don't want posts returned that are after the expiration date.
* `Snap\Database\Model\Traits\HasHierarchy`: Used for parent/child model relationships  and provides some additional convenience model methods.
* `Snap\Database\Model\Traits\HasLocalDates`:  Provides methods for displaying dates in local timezones. More info can be found at <a href="https://www.qcode.in/managing-users-timezone-in-laravel-app/" target="_blank">this url</a>.
* `Snap\Database\Model\Traits\HasPrecedence`: Used for models that have a display ordering precedence.
* `Snap\Database\Model\Traits\HasPublishedDate`: Used for models (like blog posts), that have publish dates and by default don't want posts returned that are before the publish date. 
* `Snap\Database\Model\Traits\HasStatus`: Used for models that have a "status" type column and provides some additional convenience model methods.
* `Snap\Database\Model\Traits\HasTableSchema`: Provides additional methods on the model such as:
	* `columnList`: Returns an array of the table's columns.
	* `columnInfo`: Returns an array of column schema information for the model.
	* `tableInfo`: Returns an array of the model's table information.
	* `genericColumnType`: Returns an array of the field names and there "generic" field types:
	 	* `string`: Includes field types of `var`, `varchar`, `string` `tinytext`, `text` and `longtext`.
	 	* `number`: Includes field types of `int`, `tinyint`, `smallint`, `mediumint`, `float`, `double` and `decimal`.
	 	* `datetime`: Includes field types of `datetime`, `timestamp`, `date` and `year`.
	 	* `time`: Includes field types of `time`.
	 	* `blob`: Includes field types of `blob`, `mediumblob`, `longblob` and `binary`.
	 	* `geometry`: Includes field types of `geometry`, `point`, `linestring`, `polygon`, `multipoint`, `multilinestring` and `geometrycollection`.
	 	* `enum`: Includes field types of `enum`.
	* `tableIndexes`: Returns an array with keys of `primary`, `unique` and `index` and the column names (primary only a string of the column name and `unique` and `indexes` returns and array.
	* `booleanColumns`: Returns the specified `booleanColumns` array property if specified on the model.
	* `uniqueColumns`: Returns the specified `uniqueColumns` array property if specified on the model.
* `Snap\Database\Model\Traits\HasToArrayMutated`: Used to override the default `toArray()` method to include all the mutated attributes by default (as opposed to adding them to the `$appends` array property).
* `Snap\Database\Model\Traits\HasUniqueColumns`: Used for models that have additional columns that are unique. You can specify a `static $unique` array property on the model defining additional unique columns. 
* `Snap\Database\Model\Traits\HasUserInfo`: Used for models that want to capture the `user_id` of the last authenticated person who saved the record.
* `Snap\Database\Model\Traits\IsRestorable`: Provides ability to store data to an `Snap\Database\Model\Archive` model with additional methods to retrieve and restore that information to the model.
* `Snap\Database\Model\Traits\IsSearchable`: Provides a `search($term, $fields = [])` convenience method on the model that will do a like query on the specified columns. If no columns are specified, it will look in all columns.


---
## <a id="macros"></a>Model Macros ##
Macros add additional model query builder methods.  
* `lists($name = null, $key = null)`: Will return an array of key/value pairs which can be good for dropdown lists.
* `orderByRandom()`: Will order the results in a random order.

---
## <a id="utils"></a>Helpers & DB Utils ##

#### DBUtil ####
The `Snap\Database\DBUtil` class provides some convenient methods including:
* `escape`: Escapes strings for database querying.
* `debug`: Outputs the last query run. The `log_queries` must be set to `true` in the `config/snap/db.php` config which turns on \DB::enableQueryLog().
* `columnList`: Returns the names of the table's fields in an array.
* `columnInfo`: Returns the column information from a table.
* `tableInfo`: Returns the table's column schema information.
* `genericColumnType`: Returns a more generic column type of string, number, date, datetime, time, blob, enum. 
If the column is not a recognized generic column type, it will simply return it's column type.
* `tableIndexes`: Returns the column name(s) for specific index types
* `joins`: Returns a collection of joins in a query.
* `joinExists`: Determine if a join exists already on a query.

#### Functions ####
* `query_escape($str)`: This is a convenience helper wrapper function to the `Snap\Database\DBUtil::escape` method.
* `debug_query($all = false, $format = 'screen', $return = false)`: This is a convenience helper wrapper function to the `Snap\Database\DBUtil::debug` method.
Helpful function for outputting the last query run as a SQL string. 
	* `$all`: If `$all` is set to true, it will echo/return all queries (as opposed to just the last one).
	* `$format`: There is `screen`, which will output to the screen, `comment` which will output it as an HTML comment, and `console` which will output to the javascript console.
