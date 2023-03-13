<?php

namespace Snap\Admin\Modules\Traits;

use Snap\Admin\Models\Permission;

trait BasePermissionsSelectionTrait {

    protected function getPermissionOptions()
    {
        $permissions = Permission::all();

        $options = [];
        foreach ($permissions as $permission) {
            $labelParts = explode(' ', $permission->name);
            $label = end($labelParts);
            if (!isset($options[$label])) {
                $options['*'.$label.'*'] = ucwords($label);
            }
            $options[$permission->id] = $permission->name;
        }

        return $options;
    }

    protected function processBelongToMany($key, $request, $resource)
    {
        $belongsToMany = $resource->$key();

        if ($ids = array_filter((array) $request->get($key))) {
            $belongsToMany->sync($ids);
            $belongsToMany->touch();
        }
    }

}