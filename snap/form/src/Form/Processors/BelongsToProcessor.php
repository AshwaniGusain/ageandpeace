<?php

namespace Snap\Form\Processors;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BelongsToProcessor extends AbstractInputProcessor
{
    public function run(Request $request, $resource)
    {
        /*$method = $this->getMethodName();
        $relationship = $method;

        if ($values = (array) $request->input($this->input->key)) {

            $rel = $resource->$relationship()->withoutGlobalScopes()->first();
            if (!$rel) {
                $rel = $resource->$relationship()->create($values);
                if ($rel->hasErrors() && $request->session()->get('errors')) {
                    $request->session()->get('errors')->merge($rel->getErrors());
                }
            } else {
                $rel->fill($values)->save();
                if ($rel->hasErrors() && $request->session()->get('errors')) {
                    $request->session()->get('errors')->merge($rel->getErrors());
                }
            }

            if (!$rel->hasErrors()) {
                $resource->$relationship()->withoutGlobalScopes()->associate($rel);
            }
        }*/
    }
}