# SNAP Decorator Package #

The SNAP Decorator package to "decorate" your variables into flexible objects:

* [Config](#config)
* [Types](#types)
---

---

## <a id="config"></a>Config ##
There is a `config/snap/decorators.php` a mapping of classes to keys:
```
return [
    'media'   => 'Snap\Decorator\Types\MediaDecorator',
    'date'    => 'Snap\Decorator\Types\DateDecorator',
    'link'    => ['class' => 'Snap\Decorator\Types\LinkDecorator', 'priority' => 2],
    'array'   => 'Snap\Decorator\Types\ArrayDecorator',
    'number'  => 'Snap\Decorator\Types\NumberDecorator',
    'string'  => 'Snap\Decorator\Types\StringDecorator',
    'boolean' => 'Snap\Decorator\Types\BooleanDecorator',
    'email'   => 'Snap\Decorator\Types\EmailDecorator',
];
```
```
<?php 
$link = Decorator::cast('http://laravel.com', 'link');

// OR helper function
$link = decorate('http://laravel.com', 'link');

echo $link->html('Visit Laravel', ['target' => '_blank']);
// <a href="http://laravel.com" target="_blank">Visit Laravel</a>
```

If it is not found there, be sure to publish the `Snap\Decorator\DecoratorServiceProvider`.
```
php artisan vendor:publish --provider="Snap\Database\DatabaseServiceProvider"
```
---

## <a id="types"></a>Types ##
By default, the following types are available:
* `media`: Maps to the class `Snap\Decorator\Types\MediaDecorator` with the following methods:
	* `html($attrs = [], $size = null)`:
	* `imageHtml($attrs = [], $size = null)`:
	* `audioHtml($attrs = [], $size = null)`:
	* `videoHtml($attrs = [], $size = null)`:
	* `size($size)`:
	* `path($size = null)`:
* `date`: Maps to the class `Snap\Decorator\Types\DateDecorator` with the following methods:
	* `format($format = null)`
	* `timestamp()`
`link`: Maps to the class `Snap\Decorator\Types\LinkDecorator` with the following methods:
	* `url()`:
	* `prep($str = '')`:
	* `target($link, $exts = [])`:
	* `html($link, $title = null, $attrs = [], $target = null)`:
* `array`: Maps to the class `Snap\Decorator\Types\ArrayDecorator` which translates into a `Illuminate\Support\Collection`.
* `number`: Maps to the class `Snap\Decorator\Types\ArrayDecorator` with the following methods:
	* `byte`: `Snap\Support\Helpers\NumberHelper::byte`,
    * `currency`: `Snap\Support\Helpers\NumberHelper::currency`,
    * `ordinal`: `Snap\Support\Helpers\NumberHelper::ordinal`,
    * `percent`: `Snap\Support\Helpers\NumberHelper::percent`,
    * `formatter`: `Snap\Support\Helpers\NumberHelper::formatter`,
    * `format`: PHP <a href="http://php.net?round" target="_blank">`number_format($number)`</a> function.
    * `round`: PHP <a href="http://php.net?round" target="_blank">`round($number)`</a> function.
    * `floor`: PHP <a href="http://php.net?floor" target="_blank">`floor($number)`</a> function.
    * `ceil`: PHP <a href="http://php.net?ceil" target="_blank">`ceil($number)`</a> function.
* `string`: Maps to the class `Snap\Decorator\Types\StringDecorator` with the following methods:
	* `format`: `Snap\Support\Helpers\TextHelper::htmlify`
    * `autolink`: `Snap\Support\Helpers\UrlHelper::autoLink`
    * `charlimit`: `Snap\Support\Helpers\TextHelper::characterLimiter`
    * `wordlimit`: `Snap\Support\Helpers\TextHelper::wordLimiter`
    * `stripped`: PHP <a href="http://php.net?strip_tags" target="_blank">`strip_tags($str)`</a> function. 
    * `wrap`: PHP <a href="http://php.net?wordwrap" target="_blank">`wordwrap($str)`</a> function.
    * `entities`: PHP <a href="http://php.net?htmlentities" target="_blank">`htmlentities($str)`</a> function.
    * `specialchars`: PHP <a href="http://php.net?htmlspecialchars" target="_blank">`htmlspecialchars($str)`</a> function.
    * `title`: `Illuminate\Support\Str::title`
    * `camelize`: `Illuminate\Support\Str::camel`
    * `upper`: `Illuminate\Support\Str::upper`
    * `lower`: `Illuminate\Support\Str::lower`
    * `limit`: `Illuminate\Support\Str::limit`
    * `studly`: `Illuminate\Support\Str::studly`
    * `snake`: `Illuminate\Support\Str::snake`
    * `slug`: `Illuminate\Support\Str::slug`
    * `length`: PHP <a href="http://php.net?mb_strlen" target="_blank">`mb_strlen($str)`</a> function.
    * `ucfirst`: PHP <a href="http://php.net?ucfirst" target="_blank">`ucfirst($str)`</a> function.
    * `trans`: Laravel's `trans($str)` function.
* `boolean`: Maps to the class `Snap\Decorator\Types\BooleanDecorator` with the following methods:
	* `istrue`: Returns `true` boolean value if the passed value is 'y', 'yes', 1, '1', 'true'.
	* `isfalse`: Returns `false` if the passed value is _NOT_ 'y', 'yes', 1, '1', 'true'.
* `email`: Maps to the class `Snap\Decorator\Types\EmailDecorator` with the following methods:
	* `obfuscate`:
	* `email`:
	* `mailto`:

