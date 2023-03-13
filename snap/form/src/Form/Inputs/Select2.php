<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\VueTrait;

class Select2 extends Select
{
    use VueTrait;

    protected $vue = 'snap-select2-input';

    protected $scripts = [
        'assets/snap/vendor/select2/js/select2.min.js',
        'assets/snap/js/components/form/Select2Input.js',
    ];

    protected $styles = [
        'assets/snap/vendor/select2/css/select2.min.css',
        'assets/snap/vendor/select2/css/select2-bootstrap4.min.css',
    ];

    protected $data = [
        'attrs' => [':config' => null],
        'placeholder' => true,
    ];

    protected $tagCreate;

    public function initialize()
    {
        // This isn't necessary since we have a hidden field taking care of the true value, but will leave here as a reference.
        $this->setPostProcess(function($value, $input, $request){
            if ($this->tags) {
                $ids = $this->getTagCreate();
                if ($ids) {
                    $request->request->set($this->key, $ids);
                }
            }

        }, 'beforeValidation');
    }

    public function setConfig($config)
    {
        if (is_array($config)) {
            $config = json_encode($config);
        }

        $this->setAttr(':config', $config);

        return $this;
    }

    public function getConfig()
    {
        return $this->getAttr(':config');
    }

    public function setTags($bool)
    {
        $this->setAttr(':config.tags', $bool);

        return $this;
    }

    public function getTags()
    {
        return $this->getAttrs(':config.tags');
    }

    public function setTagCreate(\Closure $closure)
    {
        $this->tagCreate = $closure;

        return $this;
    }

    public function getTagCreate()
    {
        $closure = $this->tagCreate;
        if ($closure) {
            return call_user_func($closure, request());
        }
    }

    protected function _render()
    {
        // Needed for Vue.js
        $this->setAttr(':value', $this->getValue());
        $this->setAttr('placeholder', $this->placeholder);

        return parent::_render();
    }
}