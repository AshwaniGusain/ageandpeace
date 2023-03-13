<?php

namespace Snap\Ui\Traits;

use Illuminate\Support\Str;

trait VueTrait {

    public function bootVueTrait()
    {
        if ($this->vue) {
            $this->setVueComponent($this->vue);
        }
    }

    public function setVueComponent($tag)
    {
        $this->setAttr('is', $tag);
        $this->data['vue'] = $tag;

        return $this;
    }

    public function setToVueLiteral($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                $this->setToVueLiteral($k);
            }
        } else {

            $method = 'get'.Str::studly($key);
            $val = $this->$method();
            $this->$key = null;
            unset($this->data['attrs'][$key], $this->data[$key]);
            $this->setAttr(':'.$key, $val);
        }

        return $this;

    }

    public function convertAttrsToJson($exclude = [])
    {
        $attrs = $this->getAttrs();
        $toAttrs = [];
        foreach ($attrs as $key => $val) {
            if (!in_array($key, $exclude)) {
                $toAttrs[$key] = $val;
                unset($this->data['attrs'][$key]);
            }
        }
        $jsonAttrs = json_encode($toAttrs);
        $this->data['attrs'][':attrs'] = $jsonAttrs;
    }
}
