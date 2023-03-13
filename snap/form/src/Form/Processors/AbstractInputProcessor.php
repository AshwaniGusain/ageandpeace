<?php

namespace Snap\Form\Processors;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Snap\Form\Contracts\InputProcessorInterface;
use Snap\Form\Inputs\BaseInput;

abstract class AbstractInputProcessor implements InputProcessorInterface
{
    protected $input = null;

    public function __construct(BaseInput $input)
    {
        $this->input = $input;
    }

    abstract public function run(Request $request, $resource);

    protected function getMethodName()
    {
        return Str::camel(str_replace('_id', '', $this->input->key));
    }

}