<?php

namespace Snap\Form\Inputs;

abstract class AbstractRelationshipInput extends Select2
{
    protected $method = null;
    protected $resource = null;

    public function setResource($resource)
    {
        if ($resource) {
            $this->options->forget($resource->getKey());
            $this->resource = $resource;
        }

        return $this;
    }

    public function getResource()
    {
        return $this->resource;
    }
}