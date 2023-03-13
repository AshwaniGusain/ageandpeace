<?php

namespace Snap\Admin\Ui\Components\Inputs;

class MetaFields extends RelationshipInput
{
    public function initialize()
    {
        $this->initializeMethod();

        $this->setPostProcess(function($value, $input, $request, $resource){

            $this->processRelationship($request, $resource);

        }, 'afterSave', -1);
    }

    protected function processRelationship($request, $resource)
    {
        $relationship = $this->method;
        $resource->$relationship()->set($request->input($relationship))->save();
    }

}