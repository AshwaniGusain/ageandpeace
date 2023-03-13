<?php

namespace Snap\Media\Ui\Components\Inputs;

use Illuminate\Http\UploadedFile;
use Snap\Media\Models\Media as MediaModel;
use Snap\Form\Inputs\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Media extends File
{
    protected $displayValueView = 'media::components.inputs.media';

    public function initialize()
    {
        $this->setPostProcess(function($value, $input, $request, $resource){
            $this->removeFiles($request, $resource);
        }, 'beforeSave');

        $this->setPostProcess(function($value, $input, $request, $resource){
            $this->uploadFiles($request, $resource);
        }, 'afterSave');
    }

    public function setValue($value)
    {
        if (! empty($value)) {

            if (! is_iterable($value)) {
                $value = [$value];
            }

            // @TODO... create JSON values for Vue to interpret
            $json = [];

            $mediaModule = \Admin::modules('media');

            foreach ($value as $i => $model) {

                // If an ID value is passed, we'll look it up
                if (is_numeric($model)) {
                    $model = MediaModel::find($model);
                }

                if ($model && !$model instanceof UploadedFile) {
                    $json[$i]['id'] = $model->id;
                    //$json[$i]['preview'] = 'data:'.$model->mime_type.';base64, '.base64_encode(file_get_contents($model->getPath()));
                    $json[$i]['preview'] = $model->getUrl();
                    $json[$i]['preview_url'] = $mediaModule->url('edit', ['resource' => $model->id]);
                    $json[$i]['name'] = $model->file_name;
                    $json[$i]['type'] = $model->mime_type;
                    $json[$i]['size'] = $model->size;
                    $json[$i]['custom_properties'] = $model->custom_properties;
                    $json[$i]['last_modified'] = $model->updated_at;
                }
            }

            $this->value = $json;
        }

        return $this;
    }

    public function setCollection($collection)
    {
        $this->setOption('collection', $collection);

        return $this;
    }

    public function getCollection()
    {
        return $this->getOption('collection');
    }

    public function setOption($key, $val)
    {
        $this->options[$key] = $val;

        return $this;
    }

    public function getOption($key)
    {
        return $this->options[$key] ?? [];
    }

    public function setOptions($options)
    {
        if (is_string($options)) {
            $options = json_decode($options);
        }

        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        return $this->options ?? null;
    }

    public function uploadFiles($request, $resource)
    {
        $media = [];
        if (! $this->isUploadable($request, $resource)) {
            return null;
        }

        // Simplify array by flattening
        //$files = $request->files->all();
        //$files = array_dot($files);

        // @TODO... be able to handle multiple files.
        //foreach ($files as $name => $file) {
        $name = $this->getKey();
        $file = $request->file($name);

        if ($file) {
            $options = $this->getUploadOptions($name, $request);

            $media[] = $this->uploadFile($file, $resource, $options);
        }

        return $media;
    }

    public function convertFromModel($props, $form)
    {
        $model = $form->getModel();
        $model->registerMediaCollections();
        if (!empty($model->mediaCollections[0])) {
            $this->setCollection($model->mediaCollections[0]->name);
        }

        parent::convertFromModel($props, $form);
    }

    protected function isUploadable($request, $resource)
    {
        return !empty($request->files->all()) && $resource instanceof HasMedia;
    }


    protected function getUploadOptions($name, $request)
    {
        $options = $request->input($name . '_options');

        if (!is_array($options)) {
            $options = json_decode($options, true);
        }

        $defaultOptions = ['multiple' => false, 'sanitize' => true, 'unique' => false];
        $options = ($options) ? array_merge($defaultOptions, $this->options, (array) $options) : $defaultOptions;

        return $options;
    }

    /**
     * @param $file
     * @param $resource
     * @param array $options
     * @return \Snap\Admin\Models\Media
     */
    protected function uploadFile($file, $resource, $options = [])
    {
        $collection = $options['collection'] ?? config('snap.media.collections', [])[0];
        $disk = $options['disk'] ?? 'media';

        $adder = $resource->addMedia($file);
        if (!isset($options['sanitize'])) {
            $adder->sanitizingFileName(function($fileName) {
                return clean_filename($fileName);
            });
        }

        if ( ! empty($options['unique'])) {
            // https://stackoverflow.com/questions/5349173/create-unique-image-names
            $name = substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16) . $file->getExtension();
            $adder->usingName($name);

        } elseif (isset($options['name'])) {
            $adder->usingName($options['name']);
        }

        if (isset($options['responsive']) && $options['responsive'] === true) {
            $media = $adder->withResponsiveImages()->toMediaCollection($collection, $disk);
        } else {
            $media = $adder->toMediaCollection($collection, $disk);
        }

        if (isset($options['custom_properties'])) {
            foreach ((array) $options['custom_properties'] as $key => $val) {
                $media->setCustomProperty($key, $val);
            }
            $media->save();
        }

        return $media;
    }

    protected function removeFiles($request, $resource)
    {
        $mediaIds = $request->{$this->key};

        // Normalize to array for convenience.
        if (!is_array($mediaIds)) {
            $mediaIds = array_filter([$mediaIds]);
        }

        $diff = array_filter(array_diff($resource->{$this->key}->pluck('id')->toArray(), $mediaIds));
        foreach ($diff as $id) {
            $resource->deleteMedia($id);
        }
    }

}