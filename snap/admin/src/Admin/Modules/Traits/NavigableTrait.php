<?php

namespace Snap\Admin\Modules\Traits;

use Snap\Admin\Modules\Services\NavigableService;

/**
 * Will add a menu item to the admin's main menu based on the
 * `$menuParent` and `$menuLabel` properties specified on the model.
 *
 * @autodoc true
 */
trait NavigableTrait
{
    public function registerNavigableTrait()
    {
        $this->aliasTrait('navigable', 'Snap\Admin\Modules\Traits\NavigableTrait');

        // Because the menu is needed for all modules, we register it here.
        $this->bindService('navigable', NavigableService::make($this));
    }

    public function bootNavigableTrait()
    {

    }

    public function menuLabel()
    {
        if (property_exists($this, 'menuLabel')) {
            if (! empty($this->menuLabel)) {
                return $this->menuLabel;
            } elseif ($this->menuLabel !== false) {
                return $this->pluralName();
            }
        }

        return null;
    }

    public function menuParent()
    {
        if (property_exists($this, 'menuParent')) {
            return $this->menuParent;
        }

        return $this->parent;
    }

    public function menuHandle()
    {
        if (property_exists($this, 'menuHandle')) {
            return $this->menuHandle;
        }

        return $this->fullHandle();
    }

    public function setMenuLabel($menuLabel)
    {
        $this->menuLabel = $menuLabel;

        return $this;
    }

    public function setMenuParent($menuParent)
    {
        $this->menuParent = $menuParent;

        return $this;
    }
}