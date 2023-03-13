<?php

use Snap\Support\Helpers\FileHelper;

if ( ! function_exists('filenames'))
{
    function filenames($folder, $includePath = false)
    {
        return FileHelper::filenames($folder, false);
    }
}