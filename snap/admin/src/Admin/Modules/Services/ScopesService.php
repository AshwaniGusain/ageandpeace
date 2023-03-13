<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;

/*
$scopes = ScopesService::make();
$scopes->only(['scopeActive'])
;
*/
class ScopesService
{
    public $scopeRequestParam = 'scope';
    public $active;
    public $scopes = [];

    protected $module;
    protected $request;
    protected $model;
    protected $only = [];
    protected $scoped = false;
    protected $allScopes = [];

    public function __construct(ResourceModule $module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->model = $module->getModel();
        $this->active = $request->input($this->scopeRequestParam);
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function scaffold($only = [])
    {

        $scopes = [];
        $mirror = new \ReflectionClass($this->model);

        foreach ($mirror->getMethods(\ReflectionProperty::IS_PUBLIC) as $method) {
            $methodName = $method->getName();
            $params = $method->getParameters();

            if (count($params) == 1 && preg_match('#^scope(.+)$#', $methodName, $matches)) {
                $scopes[lcfirst($matches[1])] = decamelize($matches[1]);
            }
        }

        // Handle soft deletes separately.
        if ($this->model->isSoftDelete()) {
            $scopes['onlyTrashed'] = trans('admin::resources.trash');
        }

        $this->scopes = $this->cleanScopes($scopes, $only);

        return $this;
    }

    protected function cleanScopes($scopes, $only = [])
    {
        if ($only === true) {
            $only = [];
        }
        $cleanedScopes = [];
        if (!empty($only)) {
            foreach ($scopes as $key => $label) {
                if (in_array($key, $only)) {
                    $cleanedScopes[$key] = $label;
                }
            }
        } else {
            $cleanedScopes = $scopes;
        }

        return $cleanedScopes;
    }

    public function add($key, $val = null)
    {
        if (is_array($key)) {
            foreach ($key  as $k => $v) {
                if (is_int($k)) {
                    $k  = $v;
                }
                $this->add($k, $v);
            }
        } else {
            if (empty($val)) {
                $val = $key;
            }

            $this->scopes[$key] = $val;
        }

        return $this;
    }

    //public function only($only)
    //{
    //    $only = is_array($only) ? $only : func_get_args();
    //
    //    $this->scopes = array_filter($this->allScopes, function ($value, $key) use ($only) {
    //            return (in_array($key, $only)) ? true : false;
    //        }, ARRAY_FILTER_USE_BOTH);
    //
    //
    //    return $this;
    //}

    public function query()
    {
        // Only allow this query to be run once
        if ($this->scoped) {
            return;
        }

        $query = $this->module->getQuery();
        $model = $this->module->getModel();

        $scope = $this->active;

        if (! $scope) {

            if ($model->isSoftDelete()) {
                $query = $query->withoutTrashed();
            }

        } else {

            // Will check for methods of the same name as the specified "scope" as well as
            // those with the "scope" prefix.
            $methods = [$scope, 'scope'.ucfirst($scope)];

            foreach ($methods as $method) {
                if ($scope && (method_exists($model, $method))) {
                    $query = $model->{$method}($query);
                    break;
                } elseif ($scope == 'onlyTrashed' && $model->isSoftDelete()) {
                    $query = $model->withoutGlobalScope(SoftDeletingScope::class)->{$method}($query);
                    break;
                }
            }
        }

        $this->module->setQuery($query);

        $this->scoped = true;
    }

    public function isScoped()
    {
        return $this->scoped;
    }

}