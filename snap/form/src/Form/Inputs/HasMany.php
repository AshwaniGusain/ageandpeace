<?php

namespace Snap\Form\Inputs;

use Snap\Form\Processors\HasManyProcessor;

class HasMany extends AbstractRelationshipInput
{
    protected $data = [
        'multiple' => true,
        'placeholder' => true,
        'update_reset_value' => -1,
    ];

    public function initialize()
    {
        parent::initialize();
        $this->setPostProcess(HasManyProcessor::class, 'beforeSave', -1);
    }

}