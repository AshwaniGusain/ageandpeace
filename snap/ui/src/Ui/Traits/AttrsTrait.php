<?php

namespace Snap\Ui\Traits;

trait AttrsTrait
{
    public function setAttrs($attrs)
    {
        if (isset($this->data['attrs'])) {
            $this->data['attrs'] = array_merge($this->data['attrs'], $attrs);
        } else {
            $this->data['attrs'] = $attrs;
        }

        return $this;
    }

    public function &getAttrs()
    {
        if (! isset($this->data['attrs'])) {
            $this->data['attrs'] = [];
        }

        return $this->data['attrs'];
    }

    public function appendAttr($key, $value)
    {
        $attrValue = $this->getAttr($key);
        if ($attrValue) {
            $attrValue .= ' '.$value;
        }

        $this->setAttr($key, $attrValue);

        return $this;
    }

    public function getAttrsStr()
    {
        return html_attrs($this->getAttrs());
    }

    public function setAttr($key, $value)
    {
        array_set($this->getAttrs(), $key, $value);

        return $this;
    }

    public function getAttr($key)
    {
        if (isset($this->data['attrs'])) {
            return array_get($this->getAttrs(), $key);
        }

        return null;
    }

    public function removeAttr($attrs)
    {
        if (is_string($attrs)) {
            $attrs = func_get_args();
        }

        foreach ($attrs as $key) {
            unset($this->data['attrs'][$key]);
        }

        return $this;
    }
}