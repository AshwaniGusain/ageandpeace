<?php

namespace Snap\Form\Inputs;

use Snap\Form\Processors\MorphManyProcessor;

class MorphMany extends AbstractRelationshipInput
{
    public function initialize()
    {
        parent::initialize();

        $this->setPlaceholder(true);

        $this->setPostProcess(MorphManyProcessor::class, 'beforeSave', -1);
    }

}