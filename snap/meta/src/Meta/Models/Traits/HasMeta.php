<?php

namespace Snap\Meta\Models\Traits;

use Snap\Meta\Models\Meta;
use Snap\Meta\Relationships\MetaFields;

trait HasMeta
{
    public function metaFields($metaFields = []): MetaFields
    {
        return new MetaFields($this, new Meta(), $this->getKey(), $this->getContext(), $metaFields);
    }

    public function getContext()
    {
        return get_class($this);
    }

}
