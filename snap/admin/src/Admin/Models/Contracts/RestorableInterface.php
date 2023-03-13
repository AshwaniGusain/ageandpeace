<?php

namespace Snap\Admin\Models\Contracts;

interface RestorableInterface
{

    public function restoreVersion($id = null);

    public function getRestoreData($id = null);

    public function archive();

    public function versions();

    public function version($id);

    public function nextArchiveVersion();

}