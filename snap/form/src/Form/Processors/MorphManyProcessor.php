<?php

namespace Snap\Form\Processors;

use Illuminate\Http\Request;

class MorphManyProcessor extends AbstractInputProcessor
{
    public function run(Request $request, $resource)
    {
        $relationship = $this->getMethodName();

        if ($ids = (array) $request->input($relationship)) {
            $keyName      = $resource->$relationship()->getModel()->getKeyName();
            $relResources = $resource->$relationship()->whereIn($keyName, $ids)->get();

            if (!array_filter($ids)) {
                $resource->$relationship()->delete();
            } else {
                $deleteRelResources = $resource->$relationship()->whereNotIn($keyName, $ids)->get();
                foreach ($deleteRelResources as $relResource) {
                    $relResource->delete();
                }
                //$resource->$relationship()->destroy($ids);
                $resource->$relationship()->saveMany($relResources);
            }
        } else {
            $resource->$relationship()->delete();
        }
    }

}