<?php

namespace Snap\Meta\Relationships;

use Carbon\Carbon;
use Snap\Form\Contracts\InputInterface;
use Snap\Form\FormElement;
use Snap\Meta\Models\Meta;
use Snap\Meta\MetaField;

class MetaFields
{
    protected $metaModel = null;

    protected $fields = [];

    protected $model = null;

    protected $rawData = [];

    protected $data = [];

    public function __construct($model, $metaModel, $refId, $context, array $metaFields = [])
    {
        $this->model = $model;
        $this->setMetaModel($metaModel);
        $this->addFields($metaFields);

        if ($refId) {
            $this->initMetaData($refId, $context);
        }
    }

    public function setMetaModel($metaModel)
    {
        if (is_string($metaModel)) {
            $metaModel = new $metaModel;
        }

        $this->metaModel = $metaModel;

        return $this;
    }

    public function addFields($fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof FormElement) {
                // Remove any scoped keys
                $keyArr = explode('.', $field->key);
                $key = end($keyArr);
                $this->addField($key, $field);
            } else {
                $this->addField($field, $field);
            }
        }

        return $this;
    }

    public function addField($key, $field = null)
    {
        $this->fields[$key] = $field;

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function initMetaData(int $refId, $context)
    {
        $data = $this->metaModel->where('ref_id', $refId)->where('context', $context)->get();

        foreach ($data as $d) {
            $this->rawData[$d->locale][$d->name] = $d;
            $this->data[$d->locale][$d->name] = Meta::cast($d->value, $d->type, $d->name);
        }

        return $data;
    }

    public function get($key = null, $locale = null)
    {
        if (empty($locale)) {
            $locale = $this->getDefaultLocale();
        }

        if ($key) {
            return isset($this->data[$locale][$key]) ? $this->data[$locale][$key] : null;
        }

        if (isset($this->data[$locale])) {
            return $this->data[$locale];
        }

        return null;
    }

    public function getRaw($key = null, $locale = null)
    {
        if (empty($locale)) {
            $locale = $this->getDefaultLocale();
        }

        if ($key) {
            return isset($this->rawData[$locale][$key]) ? $this->rawData[$locale][$key] : null;
        }

        return $this->rawData[$locale];
    }

    public function getType($key)
    {
        return $this->fields[$key]->getCast() ?? Meta::INPUT_TYPE_STRING;
    }

    public function set($key, $value = null, $locale = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }

        } else {
            foreach ($this->fields as $k => $field) {
                if ($field instanceof InputInterface && $field->getName(false) == $key) {
                    if (empty($locale)) {
                        $locale = $this->getDefaultLocale();
                    }

                    $metaModel = $this->createModel($k, $value, $locale);
                    $this->rawData[$locale][$k] = $metaModel;
                    $this->data[$locale][$k] = Meta::cast($metaModel->value, $metaModel->type);
                }
            }
            //if (in_array($key, array_keys($this->fields))) {
            //
            //    if (empty($locale)) {
            //        $locale = $this->getDefaultLocale();
            //    }
            //
            //    $metaModel = $this->createModel($key, $value, $locale);
            //    $this->rawData[$locale][$key] = $metaModel;
            //    $this->data[$locale][$key] = Meta::cast($metaModel->value, $metaModel->type);
            //}
        }

        return $this;
    }

    protected function createModel($key, $value, $locale)
    {
        $type = $this->getType($key);

        if ($type instanceof \Closure) {
            $type = 'Closure';
        }

        $metaModel = $this->getRaw($key);
        if (empty($metaModel)) {
            $class = get_class($this->metaModel);
            $metaModel = new $class();
        }

        $save = [
            'ref'     => $this->model->getContext().':'.$this->model->getKey(),
            'ref_id'  => $this->model->getKey(),
            'context' => $this->model->getContext(),
            'name'    => $key,
            'label'   => $this->fields[$key]->getLabel()->text,
            'type'    => $type,
            'value'   => $value,
            'locale'  => $locale,
        ];

        $metaModel->fill($save);

        return $metaModel;
    }

    public function remove($key, $locale = null)
    {
        if ($locale) {
            unset($this->rawData[$locale]);
            unset($this->data[$locale]);
        } else {
            foreach ($this->rawData as $locale => $values) {
                unset($this->rawData[$locale][$key]);
                unset($this->data[$locale][$key]);
            }
        }

        return $this;
    }

    public function save()
    {
        foreach ($this->rawData as $locale => $data) {
            foreach ($data as $d) {
                if ( ! $d->save()) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function getDefaultLocale()
    {
        return config('app.fallback_locale');
    }

}
