# Form Inputs #

The most common places to use form inputs is when configuring a resource module.
To do so, you either use the `form()` service method or the affiliated convenience `inputs()` method.
```
public function form($form, Request $request = null, $resource = null)
{
	$form->addInput('name', ['required' => true]);
	$form->addSlug('slug');
}

// OR you can use this method
public function inputs()
{
	return [
		Text::make('name', ['required' => true]),
		Slug::make('slug'),
	];
}
```
The following are the form inputs that come with the form package:

#### Inputs ####

##### Common #####
* [input](#input)
* [text](#text)
* [textarea](#textarea)
* [hidden](#hidden)
* [boolean](#boolean)
* [password](#password)
* [number](#number)
* [slug](#slug)
* [email](#email)
* [phone](#phone)
* [color](#color)
* [date](#date)
* [datetime](#datetime)
* [time](#time)
* [timezone](#timezone)
* [range](#range)
* [multiradio](#multiradio)
* [select](#select)
* [state](#state)
* [file](#file)
* [url](#url)
* [currency](#currency)


##### Multiple Selection #####
* [multicheckbox](#multicheckbox)
* [multiselect](#multiselect)
* [select2](#select2)
* [tag](#tag)

##### Relationship #####
* [belongsto](#belongsto)
* [belongstomany](#belongstomany)
* [morphtomany](#morphtomany)
* [morphmany](#morphmany)

##### Advanced #####
* [wysiwyg](#wysiwyg)
* [repeatable](#repeatable)
* [coordinates](#coordinates)
* [dependent](#dependent)
* [list](#list)
* [toggle](#toggle)
* [table](#table)
* [media](#media)
* [taxonomy](#taxonomy) Taxonomy Module
* [between](#between)
* [custom](#custom)
* [template](#template) Page Module

##### Buttons #####
* [submit](#submit)
* [button](#button)
* [reset](#reset)
* [buttonlink](#buttonlink)

---

All form inputs inherit from some base classes of `\Snap\Form\Inputs\BaseInput` which itself inherits from the abstract class of `\Snap\Form\FormElement`. These classes provide some base options: 

<a id="formelement"></a>`\Snap\Form\FormElement`
* **id:** The `id` attribute of the input. By default, it will be the same as the name attribute. A value of `false` will remove the `id` attribute.
* **type:** The handle value of an input (e.g. slug, wysiwyg)
* **order:** The order in which to render the input in the form.
* **group:** The group the input belongs to which is associated by default with a tab name.

<a id="baseinput"></a>`\Snap\Form\Inputs\BaseInput`
* **name:** The `name` attribute of the input.
* **value:** The `value` attribute of the input.
* **label:** The label to be associated next to the input.
* **scope:** The name scope. For example, a name scope of `vars[]` would change an input with a name attribute of "first_name" to vars[first_name]. The scope attribute can be assigned at the form object level to scope all names to a specific array variable in the request.
* **required:** Determines whether the input is required and will place an asterisk next to the label as well as add a required validation rule to the forms post processing rules.
* **comment:** A comment to be associated with the input (by default as a hover next to the label [?])
* **rules:** The rules to be applied 
* **validation_messages:** Validation messages for the assigned rules.
* **validation_attribute:** Validation attributes for the messages.
* **post_process:** Functions that run on posting of the form in the admin.
* **scripts:** Javascript files to include when rendering the input.
* **styles:** CSS files to include on the page when rendering the input.
* **display_value:** The display value of the input if the input is set to "display only".
* **display_only:** Determines whether an input should be in "display only" mode or editable.
* **cast:** A `Decorator` class (see docs on decorators) or closure to wrap the value of the input when rendered in a template.

---

## <a id="input"></a>Input (input) ##
The `Input` class input inherits from the `\Snap\Form\Inputs\BaseInput` and renders a basic HTML input field and is used as the base class for a number of other input types.
* Class: `\Snap\Form\Inputs\Input`
* Handle: `input`

##### Options #####
* **input_type:** The type of input to render (e.g. `text`, `hidden`, `number`...).
* **class:** Adds a `class` attribute to the input.
* **attrs:** An array of extra attributes to apply to the input.
* **placeholder:** The `placeholder` attribute for the input.
* See [BaseInput](#baseinput) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addInput('name', ['input_type' => 'text', 'placeholder' => 'Enter your name...', 'class' => 'form-control']);

// Individual Instantiation 
Input::make('name', ['input_type' => 'text', 'placeholder' => 'Enter your name...', 'class' => 'form-control']);
```
##### Output #####
```
<input type="text" name="name" placholder="Enter your name..." class="form-control">
```

---

## <a id="text"></a>Text (text) ##
The `Text` input inherits from the `\Snap\Form\Inputs\Input` class and creates a basic text box.
* Class: `\Snap\Form\Inputs\Input`
* Handle: `text`

##### Options #####
* **remaining:** Displays the count of the remaining amount of text that can be entered in the field. Default is true.
The remaining number of characters will only appear if a `maxlength` attribute is assigned to the input. If the input is scaffolded from a model, the `maxlength` attribute will automatically be applied.
* **placeholder:** Adds a `placeholder` attribute to the input.
* **max_length:** Adds the `maxlength` attribute to the input.
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addText('name', ['maxlength' => 100, 'remaining' => true, 'placeholder' => 'Enter your name...']);

// Individual Instantiation 
Text::make('name', ['maxlength' => 100, 'remaining' => true, 'placeholder' => 'Enter your name...']);
```

##### Output #####
```
<div>
	<input type="text" name="name" maxlength="100" placholder="Enter your name...">
	<div class="snap-text-input-remaining">Remaining: 100</div>
</div>
```

---

## <a id="textarea"></a>Textarea (textarea) ##
The `Textarea` input inherits from the `\Snap\Form\Inputs\Input` class and creates a basic text box.
* Class: `\Snap\Form\Inputs\Textarea`
* Handle: `textarea`

##### Options #####
* **autosize:** Will automatically resize the textarea input to fit the amount of text supplied for the value of the input. Default is `false`.
* **min_height:** The minimum height of the textarea. Default is `100`.
* **max_height:** The maximum height of the textarea (so it won't expand forever if `autosize` is true).
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addTextarea('copy', ['autosize' => true, 'min_height' => 100, 'max_height' => 600]);

// Individual Instantiation 
Textarea::make('copy', ['autosize' => true, 'min_height' => 100, 'max_height' => 600]);
```

##### Output #####
```
<textarea name="textarea" id="textarea" class="form-control" style="min-height: 100px; max-height: 600px"></textarea>
```
{{ form_element('copy', 'textarea', ['autosize' => true, 'min_height' => 100, 'max_height' => 600]) }}

---

## <a id="hidden"></a>Hidden (hidden) ##
The `Hidden` creates a basic hidden input.
* Class: `\Snap\Form\Inputs\Hidden`
* Handle: `hidden`

##### Options #####
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addHidden('secret');

// Individual Instantiation 
Hidden::make('secret');
```

##### Output #####
```
<input type="hidden" name="secret">
```
---

## <a id="boolean"></a>Checkbox (boolean) ##
The `Checkbox` input inherits from the `\Snap\Form\Inputs\Input` class and creates a form checkbox.
* Class: `\Snap\Form\Inputs\Checkbox`
* Handle: `boolean`

##### Options #####
* **enforce_if_empty:** If left unchecked, the request object will still contain the form variable but with a value of 0. Default is true.
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addBoolean('active', ['enforce_if_empty' => true]);

// Individual Instantiation 
Checkbox::make('active', ['enforce_if_empty' => true]);
```

##### Output #####
```
<input type="hidden" name="checkbox" value="0">
<input type="checkbox" name="checkbox" value="1" id="checkbox" class="form-check-input">
```
<div class="form-check">
{{ form_element('active', 'boolean', ['enforce_if_empty' => true]) }}
</div><br>

---

## <a id="password"></a>Password (password) ##
The `Password` input inherits from the `\Snap\Form\Inputs\Input` class and creates a password input.
On save, it will automatically hash the value with `Hash::make()`.
* Class: `\Snap\Form\Inputs\Checkbox`
* Handle: `password`

##### Options #####
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addPassword('password');

// Individual Instantiation 
Password::make('password');
```

##### Output #####
```
<input type="password" name="password" id="password" class="form-control">
```
{{ form_element('password', 'password') }}

---

## <a id="number"></a>Number (number) ##
The `Number` input inherits from the `\Snap\Form\Inputs\Input` class and creates a number input which provides incremental up and down buttons to manipulate the value.
* Class: `\Snap\Form\Inputs\Number`
* Handle: `number`

##### Options #####
* **max:** The maximum number that the input will allow displayed.
* **min:** The minimum number that the input will allow displayed.
* **step:** The number to increment when clicking the up or down input incrementer buttons.
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addNumber('population');

// Individual Instantiation 
Number::make('population');
```

##### Output #####
```
<input type="number" name="number" id="number" class="form-control">
```
{{ form_element('population', 'number') }}

---

## <a id="slug"></a>Slug (slug) ##
The `Slug` input inherits from the `\Snap\Form\Inputs\Input` class and creates a text input that transforms the value of another input (e.g. "name" or "title") into a "slugified" text. 
It's generally used as identifying URI segments for a page and pairs well with models that implement the [Spatie\Sluggable\HasSlug](https://github.com/spatie/laravel-sluggable) trait.
* Class: `\Snap\Form\Inputs\Slug`
* Handle: `slug`

##### Options #####
* **bound_to:** The name of the input in which the field should be bound to (e.g. "name", "title")
* **prefix:** Text to display before the input (usually used to indicate the beginning segment(s) of the URI path).
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addSlug('slug');

// Individual Instantiation 
Slug::make('slug');
```

##### Output #####
```
<input type="text" name="slug" id="slug" class="form-control">
```
{{ form_element('slug', 'slug') }}

---

## <a id="email"></a>Email (email) ##
The `Email` input inherits from the `\Snap\Form\Inputs\Input` class and creates an `email` input type. 
* Class: `\Snap\Form\Inputs\Email`
* Handle: `email`

##### Options #####
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addEmail('email');

// Individual Instantiation 
Email::make('email');
```

##### Output #####
```
<input type="email" name="email" id="email" class="form-control">
```
{{ form_element('email', 'email') }}

---

## <a id="phone"></a>Phone (phone) ##
The `Phone` input inherits from the `\Snap\Form\Inputs\Input` class and creates a `tel` input type. 
* Class: `\Snap\Form\Inputs\Phone`
* Handle: `phone`

##### Options #####
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addPhone('telephone');

// Individual Instantiation 
Phone::make('telephone');
```

##### Output #####
```
<input type="tel" name="telephone" id="telephone" class="form-control">
```
{{ form_element('telephone', 'email') }}

---

## <a id="color"></a>Color (color) ##
The `Color` input inherits from the `\Snap\Form\Inputs\Input` class and creates a `color` input type. 
* Class: `\Snap\Form\Inputs\Color`
* Handle: `color`

##### Options #####
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addColor('bgcolor');

// Individual Instantiation 
Color::make('bgcolor');
```

##### Output #####
```
<input type="color" name="bgcolor" id="bgcolor" class="form-control">
```
{{ form_element('bgcolor', 'color') }}

---

## <a id="date"></a>Date (date) ##
The `Date` input inherits from the `\Snap\Form\Inputs\Input` class and creates a date picker input. 
* Class: `\Snap\Form\Inputs\Date`
* Handle: `date`

##### Options #####
* **format:** The display format of the date.
* **options:** An array of additional options that get converted to JSON. For a list of options, visit the [daterangepicker.com](http://www.daterangepicker.com/) page
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addDate('start_date');

// Individual Instantiation 
Date::make('start_date');
```

##### Output #####
```
<input type="text" name="start_date" id="start_date" class="form-control">
```
{{ form_element('start_date', 'date') }}

---

## <a id="datetime"></a>DateTime (datetime) ##
The `DateTime` input inherits from the `\Snap\Form\Inputs\Date` class and creates a date picker input that include time. 
* Class: `\Snap\Form\Inputs\DateTime`
* Handle: `datetime`

##### Options #####
* **format:** The display format of the date.
* **options:** An array of additional options that get converted to JSON. For a list of options, visit the [daterangepicker.com](http://www.daterangepicker.com/) page
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addDate('start_datetime');

// Individual Instantiation 
Date::make('start_datetime');
```

##### Output #####
```
<input type="text" name="start_datetime" id="start_datetime" class="form-control">
```
{{ form_element('start_datetime', 'date') }}

---

## <a id="time"></a>Time (time) ##
The `Time` input inherits from the `\Snap\Form\Inputs\BaseInput` class and creates an input for time including hour, minutes and seconds (if `display_seconds` is set to `true`). 
This field is meant to be used with `time` database field types and is why there is no am\pm.
* Class: `\Snap\Form\Inputs\Time`
* Handle: `time`

##### Options #####
* **display_seconds:** The display format of the date. Default is `false`.
* **placeholder_hour:** The placeholder to display for the empty field. Default is `hh`.
* **placeholder_min:** The placeholder to display for the empty field. Default is `mm`.
* **placeholder_sec:** The placeholder to display for the empty field. Default is `ss`.
* See [BaseInput](#baseinput) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addTime('est_time');

// Individual Instantiation 
Time::make('est_time');
```

##### Output #####
```
<div>
	<input type="text" id="time" placeholder="hh" size="3" maxlength="2" class="form-control" style="width: auto; display: inline;">
	:
	<input type="text" placeholder="mm" size="3" maxlength="2" class="form-control" style="width: auto; display: inline;">
	<input name="" type="hidden" value="00:00:00">
</div>
```
{{ form_element('est_time', 'time') }}

---

## <a id="text"></a>Timezone (timezone) ##
The `Timezone` input inherits from the `\Snap\Form\Inputs\Select` class and creates a select input with all the worlds timezones. 
* Class: `\Snap\Form\Inputs\Timezone`
* Handle: `timezone`

##### Options #####
* See [Select](#select) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addTimezone('timezone');

// Individual Instantiation 
Timezone::make('timezone');
```

##### Output #####
```
<select name="timezone" id="timezone" class="form-control">
	<option value="Africa/Abidjan" selected="selected">Africa/Abidjan</option>
	<option value="Africa/Accra">Africa/Accra</option>
	<option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
	...
	<option value="Pacific/Wallis">Pacific/Wallis</option>
	<option value="UTC">UTC</option>
</select>
```
{{ form_element('timezone', 'timezone') }}

---

## <a id="range"></a>Range (range) ##
The `Range` input inherits from the `\Snap\Form\Inputs\Input` class and creates the `range` input type. 
* Class: `\Snap\Form\Inputs\Range`
* Handle: `range`

##### Options #####
* **max:** The maximum number that the input will allow displayed. Default is 10.
* **min:** The minimum number that the input will allow displayed. Default is 0.
* **step:** The number to increment when clicking the up or down input incrementer buttons. Default is 1.
* **prefix:** A string to display before the input value (e.g. $).
* **suffix:** A string to display after the input value (e.g. %).
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addRange('amount', ['step' => 5, 'min' => 0, 'max' => 50, 'prefix' => '$']);

// Individual Instantiation 
Range::make('amount', ['step' => 5, 'min' => 0, 'max' => 50, 'prefix' => '$']);
```

##### Output #####
```
<div class="row">
	<div class="col-9">
		<input name="range" id="range" type="range" max="50" min="0" step="5" list="range-list" class="form-control">
	</div>
	<div class="col-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">$</span>
			</div>
			<input type="number" max="50" min="0" step="5" class="form-control">
		</div>
	</div>
	<datalist id="range-list">
		<option>0</option>
		<option>5</option>
		...
		<option>45</option>
		<option>50</option>
	</datalist>
</div>
```
{{ form_element('amount', 'range', ['step' => 5, 'min' => 0, 'max' => 50, 'prefix' => '$']) }}

---

## <a id="multiradio"></a>MultiRadio (multiradio) ##
The `MultiRadio` input inherits from the `\Snap\Form\Inputs\BaseInput` class and creates multiple radio inputs to select from and is an alternative to using a select. 
* Class: `\Snap\Form\Inputs\MultiRadio`
* Handle: `multiradio`

##### Options #####
* See [BaseInput](#baseinput) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addMultiRadio('hobbies', ['options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']);

// Individual Instantiation 
MultiRadio::make('hobbies', ['options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']);
```

##### Output #####
```
<div class="multi-radio-field">
	<div>
		<label class="form-check-label">
			<input type="radio" name="hobbies" value="fishing">
			Fishing
		</label>
	</div>
	<div>
		<label class="form-check-label">
			<input checked="checked" type="radio" name="hobbies" value="hiking">
			Hiking
		</label>
	</div>
	<div>
		<label class="form-check-label">
			<input type="radio" name="hobbies" value="shopping">
			Shopping
		</label>
	</div>
	<div>
		<label class="form-check-label">
			<input type="radio" name="hobbies" value="running">
			Running
        </label>
	</div>
	<div>
		<label class="form-check-label">
			<input type="radio" name="hobbies" value="basketball">
			Basketball
        </label>
	</div>
	<div>
		<label class="form-check-label">
			<input type="radio" name="hobbies" value="golf">
			Golf
		</label>
	</div>
</div>
```

{{ form_element('hobbies', 'multiradio', ['options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']) }}

---

## <a id="select"></a>Select (select) ##
The `Select` input inherits from the `\Snap\Form\Inputs\BaseInput` class and creates select input. 
* Class: `\Snap\Form\Inputs\Select`
* Handle: `select`

##### Options #####
* **hide_if_one:** Will hide the select if there is only one option. Default is `false`.
* **placeholder:** The text label for the first option of the select (which has an empty value). Setting this value to `true` will output the default `Select one...`.
* **multiple:** Determines whether the select is a multi-select or not. Default is `false`.
* **options:** Can be either associative array of object, a `Collection`, a `Closure` that returns an `\Illuminate\Contracts\Support\Arrayable`, or a string containing a model class plus the method to call from the model (e.g. `Hobby::lists`).
* **disabled_options:** An array of options to be set as disabled.
* **equalize_key_value:** Determines whether a set of options that are non-associative and have integer based key values use the values as of the array as the keys also.
* **model:** A model class used to generate the options.
* See [BaseInput](#baseinput) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addSelect('hobbies', ['placeholder' => 'Select a hobby...', 'options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']);

// Individual Instantiation 
Select::make('hobbies', ['placeholder' => 'Select a hobby...', 'options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']);
```

##### Output #####
```
<select name="hobbies" id="hobbies" class="form-control">
	<option value="">Select a hobby...</option>
	<option value="fishing">fishing</option>
	<option value="hiking">hiking</option>
	<option value="shopping">shopping</option>
	<option value="running">running</option>
	<option value="basketball">basketball</option>
	<option value="golf">golf</option>
</select>
```

{{ form_element('hobbies', 'select', ['placeholder' => 'Select a hobby...', 'options' => ['fishing', 'hiking', 'shopping', 'running', 'basketball', 'golf'], 'default' => 'hiking']) }}

---

## <a id="state"></a>State (state) ##
The `Select` input inherits from the `\Snap\Form\Inputs\Select` class and creates select input with the states specified in the `config.snap.states`. 
* Class: `\Snap\Form\Inputs\State`
* Handle: `state`

##### Options #####
* See [Select](#select) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addState('state', ['placeholder' => 'Select a state...']);

// Individual Instantiation 
Select::make('state', ['placeholder' => 'Select a state...']);
```

##### Output #####
```
<select name="state" id="state" class="form-control">
	<option value="">Select a state...</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alask</option>
	...
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select>
```

{{ form_element('state', 'state', ['placeholder' => 'Select a state...']) }}

---

## <a id="file"></a>File (file) ##
The `File` input inherits from the `\Snap\Form\Inputs\Input` class and creates `file` input type that has options for integrating with the [Spatie MediaLibrary package](https://docs.spatie.be/laravel-medialibrary/v7).
* Class: `\Snap\Form\Inputs\File`
* Handle: `file`

##### Options #####
* **preview:** Displays a preview of the uploaded file (if it is previewable). Default is `true`.
* **multiple:** Will allow multiple images to be selected for upload. Default is `false`.
* **options:** These options map to related features in the [Spatie MediaLibrary package](https://docs.spatie.be/laravel-medialibrary/v7/introduction). Valid options are:
	* **collection:** The name of the [collection](https://docs.spatie.be/laravel-medialibrary/v7/working-with-media-collections/defining-media-collections) to associate the file with. Default is `default`.
	* **name:** The name to associate to with the uploaded file. Default is `true`.
	* **sanitize:** Sanitizes the file name for any special characters. Default is `true`.
	* **unique:** Creates a unique file name. Default is `false`.
	* **responsive:** [Generates associated responsive images](https://docs.spatie.be/laravel-medialibrary/v7/responsive-images/getting-started-with-responsive-images#generating-responsive-images-for-conversions). Default is `false`.
	* **custom_properties:** . Default is `false`.
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addFile('image', ['options' => ['collection' => 'images'], 'multiple' => false, 'preview' => true]);

// Individual Instantiation 
File::make('image', ['options' => ['collection' => 'images'], 'multiple' => false, 'preview' => true]);
```

##### Output #####
```
<div>
	<label class="custom-file">
		<input type="file" name="image" id="image" class="custom-file-input">
		<label for="image" class="custom-file-label">Choose file
	</label>
	<div>
		<input name="images" type="hidden">
	</div>
	<input type="hidden" name="images_options" value="&quot;{\&quot;collection\&quot;:\&quot;images\&quot;,\&quot;overwrite\&quot;:true}&quot;">
</div>
```

{{ form_element('image', 'file', ['options' => ['collection' => 'images'], 'multiple' => false, 'preview' => true]) }}

---

## <a id="url"></a>Url (url) ##
The `Url` input inherits from the `\Snap\Form\Inputs\Input` class and creates `url` input type.
* Class: `\Snap\Form\Inputs\Url`
* Handle: `url`

##### Options #####
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addUrl('link', ['placeholder' => 'http(s)://']);

// Individual Instantiation 
File::make('link', ['placeholder' => 'http(s)://']);
```

##### Output #####
```
<input type="url" name="link" id="link" placeholder="http(s)://" class="form-control">
```

{{ form_element('link', 'url', ['placeholder' => 'http(s)://']) }}

---

## <a id="currency"></a>Currency (currency) ##
The `Currency` input inherits from the `\Snap\Form\Inputs\Input` class and input field that formats the number values into a currency format.
* Class: `\Snap\Form\Inputs\Currency`
* Handle: `currency`

##### Options #####
* **symbol:** The money symbol. Default is `$`.
* See [Input](#input) for inherited options.

##### Examples #####
```
// Form Assignment
$form->addCurrency('cost', ['symbol' => '$']);

// Individual Instantiation 
Currency::make('cost', ['symbol' => '$']]);
```

##### Output #####
```
<div class="input-group" type="text">
	<div class="input-group-prepend">
		<span class="input-group-text">$</span>
	</div>
	<input type="hidden" name="cost" value="0">
	<input type="text" name="cost_formatted" id="cost" class="form-control">
</div>
```

{{ form_element('cost', 'currency', ['symbol' => '$']) }}

---