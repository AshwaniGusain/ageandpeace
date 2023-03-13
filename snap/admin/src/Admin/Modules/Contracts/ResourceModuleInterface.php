<?php

namespace Snap\Admin\Modules\Contracts;

use Illuminate\Http\Request;

interface ResourceModuleInterface
{
    public function getModel();

    public function setModel($model);

    public function getQuery();

    public function setQuery($query);

    public function getResourceData(Request $request, $resource = null);
}