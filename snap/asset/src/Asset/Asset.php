<?php 

namespace Snap\Asset;

use Snap\Support\Interpolator;
use Snap\Support\Helpers\ArrayHelper;

class Asset {

    protected $css = [];
    protected $js = [];

    public function __construct($config)
    {
        // Initialize any collections in the snap.assets config.
        $types = ['css', 'js'];
        foreach ($types as $type) {
            if (!empty($config[$type])) {
                foreach ($config[$type] as $name => $files) {
                    $this->$type($name, $files);
                }
            }
        }
    }

    public function css($name, $files = [])
    {
        return $this->collection('css', $name, $files);
    }

    public function js($name, $files = [])
    {
        return $this->collection('js', $name, $files);
    }

    protected function collection($type, $name, $files = [])
    {
        $class = ($type == 'js') ? JsCollection::class : CssCollection::class;

        // If the first parameter is an array OR a string with a .css or .js in it, then
        // we won't build on an existing collection and will instead return a fresh one.
        if (is_array($name) || $this->isFileString($name)) {
            return new $class($name);

        } else {

            if (!isset($this->$type[$name])) {
                $this->$type[$name] = new $class($files);

            } else {
                $this->$type[$name]->add($files);
            }
        }

        return $this->$type[$name];
    }

    protected function isFileString($name)
    {
        return is_string($name) && (strpos($name, '.css') !== false || strpos($name, '.js') !== false);
    }

    public function addPath($token, $path)
    {
        $this->interpolator->add($token, $path);

        return $this;
    }

}