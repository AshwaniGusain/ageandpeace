<?php

namespace Snap\Form\Processors;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Snap\Database\DBUtil;

class HasManyProcessor extends AbstractInputProcessor
{
    public function run(Request $request, $resource)
    {
        $method = $this->getMethodName();
        $relationship = $method;

        if ($ids = $request->input($relationship)) {
            $foreignKey = $resource->$relationship()->getForeignKeyName();
            $keyName = $resource->$relationship()->getModel()->getKeyName();
            $relResources = $resource->$relationship()->getModel()->whereIn($keyName, (array) $ids)->get();
            foreach($relResources as $relResource) {
                $relResource->$foreignKey = $resource->id;
                if (!$relResource->save()) {
                    $resource->addError($relResource->getErrors());
                }
            }
        }
    }

}