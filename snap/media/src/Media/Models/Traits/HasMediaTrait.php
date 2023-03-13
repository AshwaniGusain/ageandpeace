<?php

namespace Snap\Media\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as BaseHasMediaTrait;
use Snap\Media\Models\Media;

trait HasMediaTrait {

    use BaseHasMediaTrait;

    public function hasMediaRelationship($collection = null)
    {
        if (empty($collection)) {
            $collection = config('snap.media.collections')[0] ?? 'default';
        }

        $morphMany = $this->morphMany(Media::class, 'model');
        if ($collection) {
            $morphMany->where('collection_name', '=', $collection);
        }

        return $morphMany;
    }

    public function registerSingleImageMediaCollection($collection, $conversion = false, $width = 100, $height = 100)
    {
        if ($conversion === true) {
            $conversion = 'thumb';
        }

        $mediaCollection = $this->addMediaCollection($collection)->singleFile()->acceptsFile(function (File $file) {
            return $this->isImageMimeType($file);
        });

        if ($conversion) {
            $mediaCollection->registerMediaConversions(function (Media $media) use ($conversion, $width, $height) {
                $this->addMediaConversion($conversion)->width($width)->height($height)->nonQueued();
            });
        }

        return $mediaCollection;
    }

    public function registerMultiImageMediaCollection($collection, $conversion = false, $width = 100, $height = 100)
    {
        if ($conversion === true) {
            $conversion = 'thumb';
        }

        $mediaCollection = $this->addMediaCollection($collection)->acceptsFile(function (File $file) {
            return $this->isImageMimeType($file);
        });

        if ($conversion) {
            $mediaCollection->registerMediaConversions(function (Media $media) use ($conversion, $width, $height) {
                $this->addMediaConversion($conversion)->width($width)->height($height)->nonQueued();
            });
        }

        return $mediaCollection;
    }

    public function isImageMimeType($file)
    {
        return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
    }

}