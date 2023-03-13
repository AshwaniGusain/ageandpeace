<?php

namespace Snap\Asset;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Htmlable;
use IteratorAggregate;
use Snap\Support\Contracts\ToString;
use Snap\Support\Helpers\ArrayHelper;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class AssetCollection implements ArrayAccess, IteratorAggregate, Countable, Htmlable, ToString, Jsonable, JsonSerializable
{
    protected $files = [];

    protected $fileClass;

    public function __construct($files = [])
    {
        $this->add($files);
    }

    public function add($files)
    {
        $files = ArrayHelper::normalize($files);

        if (! is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            $class = $this->fileClass;
            $this->files[$file] = new $class($file);
        }

        return $this;
    }

    public function get($path = null)
    {
        if (! is_null($path)) {
            if (isset($this->files[$path])) {
                return $this->files[$path];
            }
        }

        return $this->files;
    }

    public function files()
    {
        return $this->files;
    }

    public function remove($names)
    {
        if (! is_array($names)) {
            $names = [$names];
        }

        foreach ($names as $name) {

            foreach ($this->files as $key => $file) {

                if ($file->getTargetPath() == $name) {
                    unset($this->files[$key]);
                }
            }
        }

        return $this;
    }

    public function content()
    {
        $output = '';
        foreach ($this->files as $file) {
            $output .= $file->content();
        }

        return $output;
    }

    public function version($value = true)
    {
        foreach ($this->files as $file) {
            $file->version($value);
        }

        return $this;
    }

    public function inline($attrs = [])
    {
        $output = '';
        foreach ($this->files as $file) {
            $output .= $file->inline($attrs);
        }

        return $output;
    }

    public function urls()
    {
        $urls = [];
        foreach ($this->files as $file) {
            $urls[] = $file->url();
        }

        return $urls;
    }
    //
    //public function inject($attrs = [])
    //{
    //    $output = [];
    //    foreach ($this->files as $file) {
    //        $output[] = $file->inject($attrs);
    //    }
    //
    //    return implode("\n\t", $output);
    //}

    public function jsonSerialize()
    {
        return $this->urls();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    abstract public function inject($attrs = []);

    protected function injector($tag, $src, $used, $default = [], $attrs = [])
    {
        $json = $this->toJson();
        $str = '';

        // must use javascript to do this because forms may get ajaxed in and we need to inject their CSS into the head
        if (! empty($json)) {
            $str .= "\t";
            $str .= "<script type=\"text/javascript\">\n";
            $str .= "\t";
            $str .= '(function(){
		var files = '.$json.';
		var used = [];

		// first get all the current link tags on the page so that we do not append the same file twice
		var tags = document.getElementsByTagName("'.$tag.'");
		if (tags.length){
			for (var i = 0; i < tags.length; i++){
				var tag = tags[i];
				if (tag && '.$used.'){
					used.push(tag.getAttribute("'.$src.'"));
				}
			}
		}

		// loop through the files and if they do not already exist in head, inject them
		for (var n in files){
			if (used.indexOf(files[n]) === -1){
				var elem = document.createElement("'.$tag.'");
				elem.setAttribute("'.$src.'", files[n]);
	';
            foreach ($default as $key => $val) {
                $str .= "\t\t\telem.setAttribute(\"".$key."\", \"".$val."\");\n";
            }

            $used = [$src];
            foreach ($attrs as $key => $val) {
                if (! in_array($key, $used) && ! in_array($key, $default)) {
                    $str .= "\t\t\telem.setAttribute(\"".$key."\", \"".$val."\");\n";
                }
            }
            $str .= '							document.getElementsByTagName("head")[0].appendChild(elem);
			}
		}
		})();';
            $str .= "</script>\n";
        }

        return $str;
    }

    abstract public function toHtml($attrs = []);

    public function __toString()
    {
        return $this->toHtml();
    }

    public function getIterator()
    {
        return new ArrayIterator($this->files);
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->files);
    }

    public function offsetGet($key)
    {
        return $this->files[$key];
    }

    public function offsetSet($key, $value)
    {
        $this->files[$key] = $value;
    }

    public function offsetUnset($key)
    {
        unset($this->files[$key]);
    }

    public function count()
    {
        return count($this->files);
    }
}