<?php

namespace Snap\Form\Inputs;

class TimeZone extends Select
{
    public function initialize()
    {
        $this->setOptions(\DateTimeZone::listIdentifiers(\DateTimeZone::ALL));
    }
}
