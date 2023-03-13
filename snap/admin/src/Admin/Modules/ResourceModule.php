<?php

namespace Snap\Admin\Modules;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Modules\Contracts\ResourceModuleInterface;
use Snap\Database\Model\Model;

abstract class ResourceModule extends Module implements ResourceModuleInterface
{
    protected $controller = ResourceModuleController::class;

    protected $model;

    protected $query;

    protected $ui = [
        'table'            => 'module.resource.table',
        'listing'          => 'module.resource.listing',
        'map'              => 'module.resource.map',
        'grid'             => 'module.resource.grid',
        'calendar'         => 'module.resource.calendar',
        'create'           => 'module.resource.create',
        'create_inline'    => 'module.resource.create_inline',
        'edit'             => 'module.resource.edit',
        'edit_inline'      => 'module.resource.edit_inline',
        'show'             => 'module.resource.show',
        'show_inline'      => 'module.resource.show_inline',
        'delete'           => 'module.resource.delete',
        'delete_inline'    => 'module.resource.delete_inline',
        'duplicate'        => 'module.resource.duplicate',
        'duplicate_inline' => 'module.resource.duplicate_inline',
        'input'            => 'module.resource.input',
        'form-inputs'      => 'module.resource.form-inputs',
        'compare'          => 'module.resource.compare',
    ];

    public function getModel()
    {
        if (is_string($this->model)) {
            $this->model = new $this->model();
        }

        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /*
     * Placeholder for global query adjustments
     * */
    public function query($query)
    {
        return $this;
    }

    public function getQuery()
    {
        if (is_null($this->query)) {

            $this->query = $this->getModel()->newQuery()->withoutGlobalScopes();
            if (in_array(SoftDeletes::class, class_uses($this->getModel()))) {
                $this->query->withGlobalScope(SoftDeletingScope::class, new SoftDeletingScope());
            }
        }

        $this->query($this->query);

        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function getResourceData(Request $request, $resource = null)
    {
        if (! $resource instanceof Model && is_numeric($resource)) {
            $resource = $this->model->find($resource);
            return array_merge($resource->toArray(), $this->request->all());
        }

        return $request->all();
    }
}