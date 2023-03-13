<?php

namespace Snap\Database\Model\Traits;

trait HasDisplayName {

    public function getDisplayNameKey()
    {
        if (property_exists($this, 'displayNameField') && !empty($this->displayNameField)) {
            return $this->displayNameField;
        }

        $props = array_merge($this->guarded, $this->fillable, array_keys($this->attributes));

        if (in_array('name', $props)) {
            return 'name';

        } elseif (in_array('title', $props)) {
            return 'title';

        } elseif (in_array('label', $props)) {
            return 'label';
        }

        return $this->getKeyName();
    }

    public function getDisplayNameAttribute()
    {
        if ($displayName = $this->getDisplayNameKey()) {
            return $this->{$displayName};
        }

        return '';
    }
}
