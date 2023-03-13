<?php

namespace Snap\Form\Inputs;

use Illuminate\Support\Str;
//use Snap\Admin\Ui\Components\Inputs\ModuleSelect;
use Snap\Form\Processors\BelongsToProcessor;

class BelongsTo extends AbstractRelationshipInput
{
    public function initialize()
    {
        parent::initialize();

        if (substr($this->name, -3) != '_id') {
            $newName = Str::snake(decamelize($this->name)).'_id';
            $this->setKey($newName);
            $this->setName($newName);
        }

        $this->setPostProcess(BelongsToProcessor::class, 'beforeSave', -1);
    }

}