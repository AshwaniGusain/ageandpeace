<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Snap\Admin\Models\Search;

/*
$indexable = IndexableService::make();
$indexable
    ->uri($resource->uri)
    ->title($resource->name)
    ->category($resource->category->name)
    ->excerpt($resource->content)
;
 * */
class IndexableService
{
    public $uri = '';
    public $title = '';
    public $category = '';
    public $excerpt = '';
    public $data = [];

    protected $module;
    protected $request;
    protected $resource;
    protected $fields;


    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;

        //$this->initializeEvents();
    }

    public static function make($module, $resource = null)
    {
        $service = new static($module, request());

        if ($resource) {
            $service->resource($resource);
        }

        return $service;
    }

    public function resource($resource)
    {
        if ($resource) {

            $this->resource = $resource;

            if (empty($this->uri)) {
                $this->uri = $this->module->fullUri('edit', ['resource' => $resource->id]);
            }

            if (empty($this->title)) {
                $this->title = $resource->display_name;
            }

            if (empty($this->category)) {
                $this->category = $this->module->name();
            }
        }

        return $this;
    }

    public function clear()
    {
        $this->uri = '';
        $this->title = '';
        $this->category = '';
    }

    public function fields($fields)
    {
        $this->fields = is_string($fields) ? func_get_args() : $fields;

        return $this;
    }

    public function uri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    protected function getUri($resource)
    {
        if ($this->uri instanceof \Closure) {
            return call_user_func($this->uri, $resource);

        } else {

            return $this->uri;
        }
    }

    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    protected function getTitle($resource)
    {
        if ($this->title instanceof \Closure) {
            return call_user_func($this->title, $resource);

        } else {

            return $this->title;
        }
    }

    public function excerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    protected function getExcerpt($resource)
    {
        if ($this->excerpt instanceof \Closure) {
            return call_user_func($this->excerpt, $resource);

        } else {

            return $this->excerpt;
        }
    }

    public function category($category)
    {
        $this->category = $category;

        return $this;
    }

    protected function getCategory($resource)
    {
        if ($this->category instanceof \Closure) {
            return call_user_func($this->category, $resource);

        } else {

            return $this->category;
        }
    }

    public function getIndexableData($resource)
    {
        if ($this->fields) {
            $data = [];
            foreach ($this->fields as $field) {
                $data[$field] = $resource->{$field};
            }
        } else {
            $data = $resource->getAttributes();
            $ignorable = [
                'getKeyName',
                'getCreatedAtColumn',
                'getUpdatedAtColumn',
                'getDeletedAtColumn',
                'getLastUpdatedByColumn',
                'getUpdatedByColumn',
            ];

            foreach ($ignorable as $ignore)
            {
                if (method_exists($this->resource, $ignore)) {
                    unset($data[$this->resource->{$ignore}()]);
                }
            }
        }

        $data = implode("\n\n\n", array_map(function($value){
            if (is_array($value)) {
                $value = print_r($value, true);
            }
            return strip_tags($value);
        }, $data));

        return $data;
    }

    public function indexResource($resource)
    {
        $this->resource($resource);

        $search = Search::firstOrNew(['model' => get_class($resource), 'model_id' => $resource->getKey()]);
        $search->uri = $this->getUri($resource);
        $search->category = $this->getCategory($resource);
        $search->title = $this->getTitle($resource);
        $search->content = $this->getIndexableData($resource);
        $search->excerpt = $this->getExcerpt($resource);
        $saved = $search->save();
        $this->clear();
        return $saved;
    }

    public function unIndexResource($resource)
    {
        $deleted = Search::where(['model' => get_class($resource), 'model_id' => $resource->getKey()])->delete();
        $this->clear();
        return $deleted;
    }

    public function initializeEvents()
    {
        Event::listen($this->module->eventName('beforeSave'), function(){
            if (! $this->indexResource($this->resource)) {
                //@TODO Throw error?
            }
        });

        Event::listen($this->module->eventName('afterSave'), function(){
            if (! $this->unIndexResource($this->resource)) {
                //@TODO Throw error?
            }
        });
    }
}