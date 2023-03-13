<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\VueTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class File extends Input
{
    use VueTrait;

    protected $vue = 'snap-file-input';
    protected $inputType = 'file';
    protected $options = null;

    protected $view = 'form::inputs.file';

    protected $scripts = [
        'assets/snap/js/components/form/FileInput.js',
        'assets/snap/vendor/jquery-ui/sortable.js',
        'assets/snap/js/components/form/RepeatableInput.js',
    ];

    //public function initialize()
    //{
    //    $this->setPostProcess(function($value, $input, $request, $resource){
    //        $this->uploadFiles($request, $resource);
    //    }, 'afterSave');
    //}

    protected function _render()
    {
        $this->setAttrs([
            //'is' => 'snap-file-field',
            'options' => ($json = $this->getOptions()) ? json_encode($json, JSON_FORCE_OBJECT) : null,
        ]);

        if (is_null($this->getAttr('class'))) {

            $this->setAttr('class', '');
        }

        $this->setToVueLiteral(['options']);
        $this->setToVueLiteral('value');

        return parent::_render();
    }

    public function setMultiple($isMultiple)
    {
        $this->setAttr('multiple', $isMultiple);
        $this->setOption('overwrite', !$isMultiple);

        return $this;
    }

    public function getMultiple()
    {
        return $this->getAttr('mutliple') ?? false;
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

    public function getPreview()
    {
        return $this->getAttr('preview');
    }

    public function setPreview($bool)
    {
        $this->setAttr('preview', $bool);

        return $this;
    }

    public function setPreviewUrl($bool)
    {
        $this->setAttr('preview-url', $bool);

        return $this;
    }

    public function setPreviewMaxWidth($width)
    {
        $this->setAttr('preview-max-width', $width);

        return $this;
    }

    //public function uploadFiles($request, $resource)
    //{
    //    $media = [];
    //    if (! $this->isUploadable($request, $resource)) {
    //        return null;
    //    }
    //
    //    // Simplify array by flattening
    //    //$files = $request->files->all();
    //    //$files = array_dot($files);
    //
    //    // @TODO... be able to handle multiple files.
    //    //foreach ($files as $name => $file) {
    //    $name = $this->getKey();
    //    $file = $request->file($name);
    //
    //    if ($file) {
    //        $options = $this->getUploadOptions($name, $request);
    //
    //        $media[] = $this->uploadFile($file, $resource, $options);
    //    }
    //    //}
    //
    //    return $media;
    //}
    //
    //protected function isUploadable($request, $resource)
    //{
    //    return !empty($request->files->all()) && $resource instanceof HasMedia;
    //}
    //
    //
    //protected function getUploadOptions($name, $request)
    //{
    //    $options = $request->input($name . '_options');
    //
    //    if (!is_array($options)) {
    //        $options = json_decode($options, true);
    //    }
    //
    //    $options = array_merge(['multiple' => false, 'sanitize' => true, 'unique' => false], (array) $options);
    //
    //    return $options;
    //}
    //
    ///**
    // * @param $file
    // * @param $resource
    // * @param array $options
    // * @return \Snap\Admin\Models\Media
    // */
    //protected function uploadFile($file, $resource, $options = [])
    //{
    //    $collection = $options['collection'] ?? 'default';
    //    $disk = $options['disk'] ?? 'media';
    //
    //    $adder = $resource->addMedia($file);
    //    if (!isset($options['sanitize'])) {
    //        $adder->sanitizingFileName(function($fileName) {
    //            return clean_filename($fileName);
    //        });
    //    }
    //
    //    if ( ! empty($options['unique'])) {
    //        // https://stackoverflow.com/questions/5349173/create-unique-image-names
    //        $name = substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16) . $file->getExtension();
    //        $adder->usingName($name);
    //
    //    } elseif (isset($options['name'])) {
    //        $adder->usingName($options['name']);
    //    }
    //
    //    if (isset($options['responsive']) && $options['responsive'] === true) {
    //        $media = $adder->withResponsiveImages()->toMediaCollection($collection, $disk);
    //    } else {
    //        $media = $adder->toMediaCollection($collection, $disk);
    //    }
    //
    //    if (isset($options['custom_properties'])) {
    //        foreach ((array) $options['custom_properties'] as $key => $val) {
    //            $media->setCustomProperty($key, $val);
    //        }
    //        $media->save();
    //    }
    //
    //    return $media;
    //}
}