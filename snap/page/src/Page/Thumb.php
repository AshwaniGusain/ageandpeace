<?php

namespace Snap\Page;

use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;

class Thumb
{
    public $page;
    public $config;
    public $image;
    public $queryStringParams;

    public function __construct($page, $config = null)
    {
        $this->page = $page;
        $this->config = $config ?? config('snap.pages.thumbnail');
    }

    public static function make($page, $config = null)
    {
        $thumb = new static($page, $config);

        $thumb->generate();

        return $thumb;
    }

    public function binary()
    {
        return !empty($this->config['binary']) ? $this->config['binary'] : '/usr/local/bin/node';
    }

    public function npmNinary()
    {
        return !empty($this->config['npm_binary']) ? $this->config['npm_binary']: dirname($this->binary()).'/npm';
    }

    public function path()
    {
        return rtrim($this->config['path'], '/').'/'.$this->filename();
    }

    public function url()
    {
        return \Admin::modules('page')->url('thumb', ['resource' => $this->page->id]);
    }

    public function script()
    {
        return $this->config['script'] ?? null;
    }

    public function delay()
    {
        return $this->config['delay'] ?? 0;
    }

    public function queryStringParams()
    {
        $params = $this->config['query_string_params'] ?? '';

        if (is_array($params)) {
            $params = http_build_query($params);
        }

        return $params;
    }

    public function userAgent()
    {
        return $this->config['user_agent'] ?? null;
    }

    public function fileSize()
    {
        $path = $this->path();
        if (file_exists($path)) {
            return filesize($path);
        }
    }

    public function filename()
    {
        return $this->name().'.'.$this->type();
    }

    public function name()
    {
        return $this->page->thumb_name;
    }

    public function type()
    {
        return $this->config['type'] ?? 'jpg';
    }

    public function mime()
    {
        return $this->type() == 'jpg' ? 'image/jpeg' : 'image/png';
    }

    public function captureSize()
    {
        return $this->config['capture_size'] ?? [1200, 900];
    }

    public function thumbSize()
    {
        return $this->config['thumb_size'] ?? [300, 200];
    }

    public function exists()
    {
        return file_exists($this->path());
    }

    public function options()
    {
        return $this->config['options'] ?? [];
    }

    public function generate()
    {
        $url = $this->page->url;

        list($captureWidth, $captureHeight) = $this->captureSize();
        list($width, $height) = $this->thumbSize();

        if ($qParams = $this->queryStringParams()) {
            $url .= '?'.$qParams;
        }

        Browsershot::url($url)
            ->setNodeBinary($this->binary())
            ->setNpmBinary($this->npmNinary())
            ->windowSize($captureWidth, $captureHeight)
            ->fit(Manipulations::FIT_CONTAIN, $width, $height)
            ->userAgent($this->userAgent())
            ->setOption('args', $this->options())
            ->save($this->path())
        ;

        /*
        $screenshot = new Capture($url);
        $screenshot->binPath = $this->binary();
        $screenshot->jobs->setLocation($this->jobsPath());

        list($captureWidth, $captureHeight) = $this->captureSize();
        $screenshot
            ->setImageType($this->type())
            ->setWidth($captureWidth)
            ->setHeight($captureHeight)
            ->setDelay($this->delay())
            ->setUserAgentString($this->userAgent())
            ->setOptions($this->options())
        ;

        if ($script = $this->script()) {
            $screenshot->includeJs(new \Screen\Injection\LocalPath($script));
        }

        $screenshot->save($this->path());

        // Create an image manager instance with favored driver.
        $image = $this->makeImage();

        list($width, $height) = $this->thumbSize();
        $image->fit($width, $height, function($constraint){
            $constraint->upsize();
        }, 'top-left')->save();
        */
        $this->page->thumbnail = $this->filename();

        if (!$this->page->save()) {
            $error = $this->page->getErrors();
            throw new \Exception('Could not save thumbnail: '.$error);
        }

        return true;
    }

    public function __toString()
    {
        return $this->url();
    }

    public function __call($method, $args)
    {
        $image = $this->makeImage();
        if (method_exists($image, $method)) {
            return call_user_func_array([$image, $method], $args);
        }

        throw new \BadMethodCallException("Method " . get_class($this) . "::{$method} does not exist.");
    }
}