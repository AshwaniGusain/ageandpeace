<?php

namespace Snap\Media;

use Snap\Media\Models\Media;

class MediaManager
{
    protected $meta = [];
    public function __construct($meta)
    {
        $this->meta = $meta;
    }


    public function metaForm($media, $request = null)
    {
        //$vocabulary = $request->input('vocabulary_id');

        //if (!$media instanceof Media) {
        //    if (is_numeric($media)) {
        //        $media = Media::where('id', '=', $media)->first();
        //    }
        //}


        if ($media && $request && isset($this->meta[$request->collection_name])) {
            $class = $this->meta[$request->collection_name];

            $values = ($request->input('id')) ? Media::where('id', '=', $request->input('id'))->first()->meta()->get() : [];

            if (class_exists($class)) {
                return (new $class())->getForm($values);
            }
        }
    }

}