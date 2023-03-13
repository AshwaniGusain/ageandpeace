<?php

namespace Snap\Media\Http\Controllers;

use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Media\Models\Media;
use Spatie\MediaLibrary\Helpers\File;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;

class MediaController extends ResourceModuleController
{
    public function index()
    {
        return $this->table();
    }

    public function images(Request $request)
    {
        $json = [];
        $model = new Media();
        $query = $model->newQuery();

        // Make sure only web image mimes are returned.
        $query->where(function($builder){
            $builder->orWhere('mime_type', 'image/jpeg');
            $builder->orWhere('mime_type', 'image/png');
            $builder->orWhere('mime_type', 'image/gif');
            $builder->orWhere('mime_type', 'image/svg+xml');
        });

        // If a media collection is specified in the request, grab only those that match.
        if ($request->input('collection')) {
            $query->where('collection_name', (string) $request->input('collection'));
        }

        foreach ($query->get() as $i => $media) {
            $json[$i]['url'] = $media->getUrl();
            $json[$i]['thumb'] = (in_array('thumb', $media->getMediaConversionNames())) ? $media->getUrl('thumb') : $media->getUrl();
            $json[$i]['id'] = $media->id;
            $json[$i]['title'] = $media->file_name;
            $json[$i]['mime'] = $media->mime_type;
        }

        return $json;
    }

    public function uploadFiles($resource, $request)
    {
        $file = $request->files->get('file');

        $media = [];
        if (! $file) {
            return null;
        }

        $relatedResource = $resource->model()->first();
        $path = $resource->getPath();
//        $path = $relatedResource->getMedia()[0]->getPath();

        if (file_exists($path) && filesize($path) > config('medialibrary.max_file_size')) {
            throw FileIsTooBig::create($path);
        }

        //$file->move(dirname($relatedResource->getMedia()[0]->getPath()), $resource->file_name);
        $file->move(dirname($path), $resource->file_name);
        $resource->mime_type = File::getMimetype($path);
        $resource->size = filesize($path);
        $resource->save();

        $media[] = $resource;

        return $media;
    }

}
