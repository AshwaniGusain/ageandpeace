# SNAP Asset Package #

The SNAP Asset package provides a convenience for specifying and referencing collections of CSS and javascript files for your applications:

* [Example](#example)
* [Asset Config](#config)
* [Asset Collections](#collections)
* [Asset Files](#files)
* [Facade](#facade)
* [Helpers](#helpers)
* [CSS](#css)
* [Javascript](#js)

---

## <a id="example"></a>Example ##
The follow is an example and is what is actually used for rendering the SNAP admin.

```
{!! css('admin')->version() !!}
{!! css('styles')->version()->links() !!}
{!! js('snap')->version() !!}
{!! js('scripts')->version()->scripts(['defer' => 'defer']) !!}
{!! js('admin')->version()->scripts(['defer' => 'defer']) !!} 
```

---

## <a id="config"></a>Asset Config ##
The asset config is located at `config/snap/assets.php` and allows you to specify CSS and javascript files grouped by a key that can be used for rendering as shown in the example above: 
```
return [
    'css' => [
        'admin' => [
            'assets/snap/css/admin.css'
        ],
    ],

    'js' => [
        'snap' => [
            'assets/snap/js/SNAP.js'
        ],
        'admin' => [
            'assets/snap/js/components/common.js',
            'assets/snap/js/init.js',
        ]
    ],

]; 
```
---

## <a id="collections"></a>Asset Collections ##
`$collection->add($path)`
`$collection->get($path = null)`
`$collection->files()`
`$collection->remove($names)`
`$collection->content()`
`$collection->version()`
`$collection->inline()`
`$collection->urls()`
`$collection->toJson()`
`$collection->inject()`

---
## <a id="files"></a>Asset Files ##
`$file->url(version = null)`
`$file->name()`
`$file->dir()`
`$file->basename()`
`$file->ext()`
`$file->timestamp()`
`$file->path()`
`$file->content()`
`$file->version($value = true)`
`$file->tag($attrs = null)`
`$file->inline($attrs = null)`


---

## <a id="facade"></a>Facade ##
You can use the `Asset` face to return an asset collection to further manipulate:
* `Asset::css($name, $files = [])`: Returns an `\Snap\Asset\CssCollection` object. The `$name` parameter is used to associate the files to a rendering group. For example, you may have one called `head` which you use to render in the head of your document.
* `Asset::js($name, $files = [])`: Returns an `\Snap\Asset\JsCollection` object. Similar to the `css()` function , the `$name` parameter is used to associate the files to a rendering group. For example, you may have one called `head` which you use to render in the head of your document.


---

## <a id="helpers"></a>Helpers ##
* `css($name, $files = [])`: An alias to the `Asset::css($name, $files = [])` facade method. The `$name` parameter is used to associate the files to a rendering group. For example, you may have one called `head` which you use to render in the head of your document.
* `js($name, $files = [])`: An alias to the `Asset::js($name, $files = [])` facade method. Similar to the `css()` function , the `$name` parameter is used to associate the files to a rendering group. For example, you may have one called `head` which you use to render in the head of your document.
* `link_tag($href = '', string $rel = 'stylesheet', string $type = 'text/css', string $title = '', string $media = '')`: To add additional attributes to the tag, pass a key value attribute array.
* `script_tag($src = '')`: To add additional attributes to the tag, pass a key value attribute array.

---

## <a id="css"></a>CSS ##

---

## <a id="js"></a>Javascript ##
