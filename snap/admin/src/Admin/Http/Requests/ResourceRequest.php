<?php

namespace Snap\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest
{
    public $module = null;

    public function getModule()
    {
        if (!isset($this->module)) {
            $route = $this->route();
            if ($route) {
                $actions = $this->route()->getAction();
                if (isset($actions['module'])) {
                    $this->module = \Module::get($actions['module']);
                }
            }
        }

        return $this->module;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $module = $this->getModule();
        $resource = $this->route('resource');

        if (!$resource) {
            $model = $this->getModule()->getModel();
            $resource = new $model();
        }

        $module->runHook('beforeValidation', [$resource, $this, $module]);

        // Set resource values from the request.
        $this->fillResource($resource);

        if ($formService = $module->service('form')) {
            $rules = $formService->resource($resource)->getValidationRules();

            // If a key isn't any the Request::all(), then we will remove the rules
            // for instances where only some inputs are being updated (e.g. inline editing).
            $requestKeys = array_keys($this->all());
            $rules = array_filter($rules, function($key) use ($requestKeys) {
                return in_array($key, $requestKeys);
            }, ARRAY_FILTER_USE_KEY);

            return $rules;
        }

        return [];
    }

    public function fillResource($resource)
    {
        // Set resource values from the request
        $data = $this->getModule()->getResourceData($this, $resource);
        $resource->fill($data);

        return $resource;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        if ($formService = $this->getModule()->service('form')) {
            return $formService->getValidationMessages();
        }

        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        if ($formService = $this->getModule()->service('form')) {
            return $formService->getValidationAttributes();
        }

        return [];
    }

    /**
     * @return array
     */
    public function validationData()
    {
        if ($formService = $this->getModule()->service('form')) {
            return $formService->getValidationValues();
        }

        return $this->all();
    }
}
