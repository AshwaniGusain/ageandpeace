<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;

/*
$preview = PreviewService::make();
$preview
    ->prefix('/about/')
    ->fromInput('slug')
;
 * */
class PreviewService
{
    //public $prefix = '';
    public $slugInput = 'slug';
    public $prefix = '';
    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        //$this->prefix = '/'.$this->module->handle();
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function slugInput($name)
    {
        $this->slugInput = $name;

        return $this;
    }

    public function prefix($prefix)
    {
        $this->prefix = rtrim($prefix, '/');

        return $this;
    }

    //protected function initializePreview($ui, $request)
    //{
    //    //$ui->heading->setPreviewButton(true);
    //    //$this->initializePreviewForm($ui->form, $request);
    //}
    //
    //public function getPreviewRouteUrl($resource)
    //{
    //    $routeName = $this->previewSlugInput ?? null;
    //    if ($routeName) {
    //        $param = $resource->{$this->getPreviewSlugInput()};
    //        return route($routeName, [$this->getPreviewSlugInput() => $param]);
    //    }
    //
    //    return null;
    //}
    //
    //public function getPreviewRouteCallback()
    //{
    //    return $this->previewRouteCallback ?? function(){
    //            return '';
    //        };
    //}
    //
    //public function getPreviewSlugInput()
    //{
    //    return $this->previewSlugInput ?? 'slug';
    //}
    //
    //public function getPreviewUriPrefix()
    //{
    //    return $this->previewUriPrefix ?? '/';
    //}
    //
    //public function getPreviewUri($resource)
    //{
    //    $uri = $this->getPreviewSlugInput();
    //
    //    if ($prefix = $this->getPreviewUriPrefix()) {
    //        $uri = trim($prefix, '/').'/'.$uri;
    //    }
    //
    //    return $uri;
    //}
    //
    //protected function initializePreviewForm($form, Request $request = null, $resource = null)
    //{
    //    $form->get($this->inputName)->setPrefix($this->getPreviewUriPrefix());
    //}

}