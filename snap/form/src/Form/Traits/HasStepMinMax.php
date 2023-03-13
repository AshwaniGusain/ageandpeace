<?php

namespace Snap\Form\Fields\Traits;

trait HasStepMinMax
{
    public function setMax($max)
    {
        $this->setAttr('max', $max);

        return $this;
    }

    public function getMax()
    {
        return $this->getAttr('max');
    }

    public function setMin($min)
    {
        $this->setAttr('min', $min);

        return $this;
    }

    public function getMin()
    {
        return $this->getAttr('min');
    }

    public function setStep($step)
    {
        $this->setAttr('step', $step);

        return $this;
    }

    public function getStep()
    {
        return $this->getAttr('step');
    }

}