<?php

namespace Snap\Form\Inputs;

use Snap\Form\Processors\RelationshipToManyProcessor;

abstract class AbstractRelationshipToManyInput extends AbstractRelationshipInput
{
    protected $data = [
        'multiple' => true,
        'placeholder' => true,
    ];

    public function initialize()
    {
        parent::initialize();

        $this->setPostProcess(RelationshipToManyProcessor::class, 'afterSave', -1);
    }
}