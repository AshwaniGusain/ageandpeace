<?php

namespace Snap\Form\Contracts;

use Illuminate\Http\Request;
use Snap\Database\Model\Model;
use Snap\Form\Inputs\BaseInput;

interface InputProcessorInterface
{
    public function __construct(BaseInput $input);
    public function run(Request $request, Model $resource);
}
