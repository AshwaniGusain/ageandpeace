<?php

namespace Snap\Support\Traits;

trait HasBootableTraits {

    private $_traits;
    private $_traitAliases = [];
    
    protected function bootTraits()
    {
        foreach($this->traits() as $trait) {
            $class = class_basename($trait);
            $method = 'boot'.$class;
            if (method_exists($this, $method)) {
                app()->call([$this, $method]);
            }
        }
    }

    public function traits()
    {
        if (is_null($this->_traits)) {
            $this->_traits = class_uses_recursive($this);
        }

        return $this->_traits;
    }

    public function hasTrait($trait)
    {
        if (isset($this->_traitAliases[$trait])) {
            return true;
        }

        $traits = $this->traits();

        return (isset($traits[$trait])) ? true : false;
    }

    public function aliasTrait($alias, $trait)
    {
        $traits = $this->traits();

        if (isset($traits[$trait])) {
            $this->_traitAliases[$alias] = ltrim($trait, '\\');
        }

        return $this;
    }

    public function getTraitAlias($trait)
    {
        $traitAliases = array_flip($this->_traitAliases);

        if (isset($traitAliases[$trait])) {
            return $traitAliases[$trait];
        }
    }

    public function getTraitAliases()
    {
        return $this->_traitAliases;
    }
}