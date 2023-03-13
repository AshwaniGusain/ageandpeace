<?php

namespace Snap\Ui\Traits;

use Snap\Support\Helpers\ArrayHelper;

trait CssClassesTrait {

    public function bootCssClassesTrait()
    {
        if ( ! isset($this->data['class'])) {
            $this->data['class'] = '';
        }
    }

    public function addClass($class)
    {
        $class = ArrayHelper::normalize($class);
        $classes = array_merge($this->getClassToArray(), $class);
        $this->setClass($classes);

        return $this;
    }

    public function setClass($class)
    {
        $class = ArrayHelper::normalize($class);

        $attrClasses = ArrayHelper::normalize($this->data['attrs']['class'] ?? []);
        $currentClasses = ArrayHelper::normalize($this->data['class']);

        $classes = array_unique(array_filter(array_merge($currentClasses, $attrClasses, $class)));

        $classesStr = implode(' ', $classes);

        $this->data['class'] = $this->data['attrs']['class'] = $classesStr;
        
        return $this;
    }

    public function removeClass($class)
    {
        ArrayHelper::remove($this->getClassToArray(), $class);

        return $this;
    }

    public function getClass()
    {
        return implode(' ', array_filter($this->getClassToArray()));
    }

    public function getClassToArray()
    {
        if ( ! empty($this->data['class'])){
            return ArrayHelper::normalize($this->data['class']);
        }
        return [];
    }
}