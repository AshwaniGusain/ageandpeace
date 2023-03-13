<?php

namespace Snap\Admin\Modules\Contracts;

interface ModuleInterface
{
    public function handle();
    public function parentHandle();
    public function fullHandle();
    public function name();
    public function pluralName();
    public function description();
    public function parent();
    public function icon();
    public function getController();
    public function url($uri = '', $params = [], $qs = false);
    public function baseUri();
    public function fullBaseUri();
    public function uri($uri = '', $params = []);
    public function fullUri($uri = '', $params = []);
    public function currentUri();
    public function currentFullUri();
    public function routes();
    //public function publicRoutes();
    public function getNamespace();
    public function config($key = null, $default = null);
    public function getConfig($key = null, $default = null);
    public function setConfig($key, $val = null);
    public function path($file = null);
    public function modules();
    public function module($handle);
    public function isCurrent();
    public function ui($handle, $params = [], $callback = null);
    public static function eventName($name);
    public function permissions();
    public function hasPermission($permission);
    public function version();
    public function isResource();
    public function install();
    public function uninstall();
}