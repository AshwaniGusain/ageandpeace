<?php

namespace Snap\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Snap\Admin\Http\Requests\ResourceRequest;
use Snap\Admin\Models\User;
use Snap\Database\Model\Relationships\MetaFields;
use Snap\Support\Helpers\ArrayHelper;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ResourceModuleController extends ModuleController
{
    protected $resource;
    protected $errors;

    public function initialize()
    {
        parent::initialize();

        $this->errors = bag();

        if ($this->module) {
            $this->model = $this->module->getModel();
            $this->app->instance('ActiveSnapModel', $this->model);
        }
    }

    public function index()
    {
        return $this->table();
    }

    public function table()
    {
        return $this->module->ui('table', function ($ui) {

            /* Put your custom code table view code here:
            $ui->heading->setTitle('This is a new heading!');
            $ui->table
                ->setColumns(['name', 'email'])
                ->addIgnored('updated_at')
                ->addAction('{id}/show', 'SHOW')
                ->addAction(function($values){
                    return '<a href="'.$values['id'].'/delete">DELETE</a>';
                });
            */
        })->render();
    }

    public function listing()
    {
        return $this->module->ui('listing', function ($ui) {

        })->render();
    }

    public function grid()
    {
        return $this->module->ui('grid', function ($ui) {

        })->render();
    }

    public function calendar()
    {
        return $this->module->ui('calendar', function ($ui) {

        })->render();
    }

    public function map()
    {
        return $this->module->ui('map', function ($ui) {

        })->render();

    }

    public function create()
    {
        return $this->module->ui('create', function ($ui) {

        })->render();
    }

    public function createInline()
    {
        return $this->module->ui('create_inline', function ($ui) {

        })->render();
    }

    public function insert(ResourceRequest $request)
    {
        $model = $this->module->getModel();
        return $this->save(new $model($this->module->getResourceData($request)), $request, 'edit');
    }

    public function show($resource)
    {
        return $this->module->ui('show', ['resource' => $resource], function ($ui) use ($resource) {

        })->render();
    }

    public function showInline($resource)
    {
        return $this->module->ui('show_inline', ['resource' => $resource], function ($ui) use ($resource) {

        })->render();
    }

    public function edit($resource)
    {
        return $this->module->ui('edit', ['resource' => $resource], function ($ui) use ($resource) {

        })->render();
    }

    public function editInline($resource)
    {
        return $this->module->ui('edit_inline', ['resource' => $resource], function ($ui) use ($resource) {

        })->render();
    }

    public function duplicate($resource)
    {
        $this->request->resource = $this->module->service('form')->resolveDuplicate($resource, $this->request);
        return $this->module->ui('duplicate', ['resource' => $this->request->resource], function ($ui) use ($resource) {

        })->render();
    }

    public function duplicateInline($resource)
    {
        $this->request->resource = $resource->replicate();
        return $this->module->ui('duplicate_inline', ['resource' => $resource], function ($ui) use ($resource) {

        })->render();
    }

    public function input($input, $resource = null)
    {
        return $this->module->ui('input', [
            'input'    => $input,
            'resource' => $resource,
            'value'    => $this->request->input('value'),
        ], function ($ui) use ($resource) {

        })->render();
    }

    public function update($resource, ResourceRequest $request)
    {
        return $this->save($resource, $request, 'edit');
    }

    public function delete($ids)
    {
        return $this->module->ui('delete', ['ids' => $ids], function ($ui) {

        })->render();
    }

    public function deleteInline($ids)
    {
        return $this->module->ui('delete_inline', ['ids' => $ids], function ($ui) {

        })->render();
    }

    public function doDelete()
    {
        if ( ! $this->request->input('ids')) {
            abort(404);
        }

        $ids = ArrayHelper::normalize($this->request->input('ids'));
        foreach ($ids as $id) {
            $resource = $this->model->withoutGlobalScopes()->find($id);

            $this->module->runHook('beforeDelete', [$resource, $this->request, $this->module]);

            $deleteMethod = 'delete';
            if ($resource->isSoftDelete()) {
                if ($this->module->service('deletable')->allowForceDelete && $resource->trashed()) {
                    $deleteMethod = 'forceDelete';
                }
            }

            if (!$this->module->service('deletable')->canDelete || ! $resource->$deleteMethod()) {
                return $this->errorResponse([trans('admin::resources.delete_error')]);
            }

            $this->module->runHook('afterDelete', [$resource, $this->request, $this->module]);
        }

        $redirect = $this->module->url();

        return $this->successResponse(trans('admin::resources.delete_success'), ['values' => $ids], $redirect);
    }

    public function unTrash($resource, ResourceRequest $request)
    {
        // Must refresh to get the correct data to update
        $resource
            ->refresh()
            ->restore();

        $redirect = $this->module->url('edit', ['resource' => $resource]);

        return $this->successResponse(trans('admin::resources.untrash_success'), ['values' => $resource->getAttributes()], $redirect);
    }

    protected function save($resource, $request, $redirect = null)
    {
        $this->app->instance('ActiveSnapResource', $resource);

        // We'll use transactions here to make sure everything is glorious among the data.
        DB::beginTransaction();

        // The object is already filled from the ResourceRequest object
        //$resource->fill($request->all());
        $this->module->runHook('beforeSave', [$resource, $request, $this->module]);

        // Certain relationships need to create the ID first for the resource to save.
        //$this->beforeSaveRelationships($resource);

        // Validation has already happened in request so no need to do it again here.
        if ( ! $resource->save(['validate' => false])) {
            $this->errors->merge($resource->getErrors());
        }

        // Certain relationships depend on the resource ID and are saved after.
        //$this->afterSaveRelationships($resource);

        // Then upload any files...
        //$this->uploadFiles($resource, $request);

        $this->module->runHook('afterSave', [$resource, $request, $this->module]);

        if ($this->errors->isNotEmpty()) {
            // Bad news... no worky...
            DB::rollback();
            return $this->errorResponse($this->errors);
        }

        // If everything is glorious, we'll commit the transaction.
        DB::commit();

        $redirect = $request->input('__redirect__') ?? $redirect;

        if ($redirect == 'create') {
            $redirect = $this->module->url('create');

        } elseif ($redirect == 'edit') {
            $redirect = $this->module->url('edit', ['resource' => $resource]);

        } elseif ($redirect == 'edit_inline') {
            $redirect = $this->module->url('edit_inline', ['resource' => $resource]);

        } elseif ($redirect == 'index') {
            $redirect = $this->module->url();
        }

        return $this->successResponse(trans('admin::resources.success_save'),
            [$this->model->getKeyName() => $resource->id], $redirect);
    }

    public function upload($resource)
    {
        $media = $this->uploadFiles($resource, $this->request);

        return ['file' => ['url' => $media[0]->getUrl(), 'id' => $media[0]->id]];
    }

    public function uploadFiles($resource, $request)
    {
        $media = [];
        if (! $this->isUploadable($request)) {
            return null;
        }

        // Simplify array by flattening
        $files = $request->files->all();
        $files = array_dot($files);

        foreach ($files as $name => $file) {

            $options = $this->getUploadOptions($name, $request);
            $media[] = $this->uploadFile($file, $resource, $options);
        }

        return $media;
    }

    protected function isUploadable($request)
    {
        return !empty($request->files->all()) && $this->model instanceof HasMedia;
    }

    protected function getUploadOptions($name, $request)
    {
        $options = $request->input($name . '_options');

        if (!is_array($options)) {
            $options = json_decode($options, true);
        }

        $options = array_merge(['multiple' => false, 'sanitize' => true, 'unique' => false], (array) $options);

        return $options;
    }

    /**
     * @param $file
     * @param $resource
     * @param array $options
     * @return \Snap\Media\Models\Media
     */
    protected function uploadFile($file, $resource, $options = [])
    {
        $collection = $options['collection'] ?? 'default';
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

    public function sort()
    {
        if ($data = $this->request->input('data')) {

            $precedenceColumn = $this->module->service('listing')->precedenceColumn;
            $parentColumn     = $this->module->service('listing')->parentColumn;

            foreach ($data as $key => $val) {
                if ( ! empty($val['id'])) {
                    $resource = $this->model->find($val['id']);
                    if ($resource) {
                        if ($precedenceColumn) {
                            $resource->setAttribute($precedenceColumn, $key);
                        }

                        if ($parentColumn) {
                            $resource->setAttribute($parentColumn, $val['parent_id']);
                        }

                        if ( ! $resource->save()) {
                            return $this->errorResponse([trans('admin::resources.save_error')]);
                        }
                    }
                }
            }

            return $this->successResponse(['values' => $data]);
        }
    }

    protected function successResponse($msg, $data = [], $redirect = null)
    {
        if ($this->request->ajax()) {
            $json = ['success' => true, 'message' => $msg, 'data' => $data];

            return response($json, Response::HTTP_OK);
        } else {

            if (empty($redirect)) {
                $redirect = url()->previous();
            }

            return redirect($redirect)->with('success', $msg);
        }
    }

    protected function errorResponse($errors)
    {
        $errors = $this->formatAjaxError($errors);

        $json = ['success' => false, 'errors' => $errors];

        return response($json, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function formatAjaxError($errors)
    {
        $e = [];

        if ($errors instanceof MessageBag) {
            $errors = $errors->getMessages();
        }

        foreach ($errors as $key => $input) {
            $input = (array) $input;
            foreach ($input as $message) {
                $e[] = ['input' => $key, 'message' => $message];
            }
        }

        return $e;
    }

    public function compare($resource, $version)
    {
        $version = $resource->version($version);
        if ( ! $version) {
            abort(404);
        }
        return $this->module->ui('compare', ['resource' => $resource, 'version' => $version])->render();
    }

    public function restore($resource)
    {
        if ($version = $this->request->input('version')) {
            if ($resource->restoreVersion($version)) {
                $redirect = $this->module->url('edit', [$resource->id]);
                return $this->successResponse(trans('admin::resources.restore_success'), $resource->toArray(), $redirect);
            }
        }

        return $this->errorResponse([trans('admin::resources.restore_error')]);
    }

    public function ajax($name)
    {
        $ajaxService = $this->module->service('ajax');
        if ($ajaxService && $method = $ajaxService->getModuleMethod($name)) {
            return call_user_func([$this->module, $method], $this->request);
        }

        return $this->errorResponse([trans('admin::resources.no_data')]);
    }

    public function export(Request $request)
    {
        $exportService = $this->module->service('export');
        $this->module->export($exportService, $request);
        return $exportService->download();
    }

    public function loading()
    {
        return '<div class="snap-loader" style="position: relative; width: 100%; height: 100%;"><img src="'.asset('assets/snap/admin/images/spinner.gif').'" style="position: relative; top: 50%; margin: -16px auto 0 auto; width: 32px; height: 32px; display: block;"></div>';
    }


}
