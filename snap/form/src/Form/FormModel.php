<?php

namespace Snap\Form;

use Snap\Database\DBUtil;

class FormModel
{
    /**
     * @var \Snap\Form\Form
     */
    protected $form;

    /**
     * @var \Illuminate\Foundation\Application|mixed|null|\Snap\Form\FormFactory
     */
    protected $factory;

    /**
     * @var
     */
    protected $model;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $hints = [];

    /**
     * @var array
     */
    protected $props = [];

    /**
     * @var bool
     */
    protected $relationships = true;

    /**
     * @var bool
     */
    protected $annotations = true;

    /**
     * FormModel constructor.
     *
     * @param \Snap\Form\Form $form
     * @param $model
     * @param array $options
     */
    public function __construct(Form $form, $model, $options = [])
    {
        $this->form = $form;
        $this->model = $model;
        $this->factory = $form->getFactory();

        $this->hints = $this->factory->hints();

        if (isset($options['props'])) {
            $this->withProps($options['props']);
        }

        if (isset($options['relationships'])) {
            $this->withRelationships($options['relationships']);
        }

        if (isset($options['hints'])) {
            $this->addHints($options['hints']);
        }

        if (isset($options['annotations'])) {
            $this->withAnnotations($options['annotations']);
        }

        $this->options = $options;
    }

    /**
     * @param $model
     * @param array $options
     * @return \Snap\Form\FormModel
     */
    public static function make($model, $options = [])
    {
        $form = \Form::make();

        return new static($form, $model, $options);
    }

    /**
     * @return \Snap\Form\Form
     */
    public function build()
    {
        $values = $this->getValues();
        //$this->form->setValues($values);

        $columns = DBUtil::tableInfo($this->model->getTable());

        // Setup general model variables we'll use for setting up the different inputs
        $unique = (method_exists($this->model, 'uniqueColumns')) ? $this->model->uniqueColumns() : [];
        $booleans = (method_exists($this->model, 'booleanColumns')) ? $this->model->booleanColumns() : [];
        $required = (method_exists($this->model, 'columnsWithRule')) ? array_merge($this->model->columnsWithRule('required'), (array) $unique) : null;

        foreach ($columns as $name => $column) {

            // Ignore any of these ones by default.
            $ignorable = $this->getIgnorableFields($columns->keys(), $this->model);

            if (in_array($name, $ignorable)) {
                continue;
            }

            $props = [];

            if (in_array($name, $booleans)) {
                $column['type'] = 'boolean';
            }

            if (isset($values[$name])) {
                $props['value'] = $values[$name];
            } elseif (! empty($column['default'])) {
                $props['value'] = $column['default'];
            }

            if (isset($column['comment'])) {
                $props['comment'] = $column['comment'];
            }

            // Add required property if they have a required validation rule assigned to them.
            if (! empty($required) && in_array($name, $required)) {
                $props['required'] = true;
            }

            if (!$this->hasProp($name, 'type')) {
                $props['type'] = $this->deriveInputTypeFromHints($column);
            }
            $props = $this->mergeProps($name, $props);

            $input = $this->form->create($name, $props);
            $input->convertFromModel($column, $this);
            $this->form->add($input);
        }

        if (! empty($this->annotations)) {
            $this->buildAnnotationInputs();
        }

        if (isset($this->options['only'])) {
            $this->form->only($this->options['only']);
        }

        if (isset($this->options['except'])) {
            $this->form->except($this->options['except']);
        }

        if (! empty($this->relationships)) {
            $this->buildRelationshipInputs($required);
        }

        // Order it by how the fillable array is setup.
        $this->form->order($this->model->getFillable());

        return $this->form;
    }

    /**
     * @param $props
     * @return $this
     */
    public function withProps($props)
    {
        $this->props = $props;

        return $this;
    }

    /**
     * @param $key
     * @param array $props
     * @return array
     */
    public function mergeProps($key, $props = [])
    {
        if (isset($this->props[$key])) {
            $props = array_merge($this->props[$key], $props);
            if (isset($this->props[$key]['type'])) {
                $props['type'] = $this->props[$key]['type'];
            }
        }

        return $props;
    }

    /**
     * @param $key
     * @param $propName
     * @return bool
     */
    public function hasProp($key, $propName)
    {
        return isset($this->props[$key][$propName]);
    }

    /**
     * @param bool $with
     * @return $this
     */
    public function withRelationships($with = true)
    {
        $this->relationships = $with;

        return $this;
    }

    /**
     * @param bool $with
     * @return $this
     */
    public function withAnnotations($with = true)
    {
        $this->annotations = $with;

        return $this;
    }

    /**
     * @param $hints
     * @param bool $merge
     * @return $this
     */
    public function addHints($hints, $merge = true)
    {
        if ($merge) {
            $this->hints = array_merge_recursive($this->hints, $hints);
        } else {
            $this->hints = array_merge($this->hints, $hints);
        }

        return $this;
    }

    /**
     * @param $name
     * @param null $matches
     * @param string $type
     * @param bool $merge
     * @return $this
     */
    public function addHint($name, $matches = null, $type = 'type', $merge = true)
    {
        if ($merge && isset($this->hints[$type][$name])) {
            $matches = array_merge($this->hints[$type][$name], $matches);
        }

        $this->hints[$type][$name] = $matches;

        return $this;
    }

    /**
     * @param $name
     * @param null $matches
     * @param bool $merge
     * @return \Snap\Form\FormModel
     */
    public function addNameHint($name, $matches = null, $merge = true)
    {
        return $this->addHint($name, $matches, 'name', $merge);
    }

    /**
     * @param $name
     * @param null $matches
     * @param bool $merge
     * @return \Snap\Form\FormModel
     */
    public function addTypeHint($name, $matches = null, $merge = true)
    {
        return $this->addHint($name, $matches, 'type', $merge);
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $column
     * @return int|mixed|string
     */
    protected function deriveInputTypeFromHints($column)
    {
        // Look through hints to determine type.
        foreach (['name', 'type'] as $hint) {

            foreach ($this->hints[$hint] as $key => $val) {
                if (is_array($val) && in_array($column[$hint], $val)) {
                    $type = $key;
                    break 2;
                } elseif (is_callable($val)) {
                    $type = call_user_func($val, $column, $this->model);
                    break 2;
                } elseif (is_string($val) && preg_match('#'.$val.'#U', $val)) {
                    $type = $key;
                    break 2;
                }
            }
        }

        if (empty($type)) {
            $type = $column['type'];
        }

        return $type;
    }

    /**
     * @return $this
     */
    protected function buildRelationshipInputs($required)
    {
        if (method_exists($this->model, 'getRelationshipTypes')) {
            $relationships = $this->model->getRelationshipTypes();

            if (! empty($relationships)) {

                foreach ($relationships as $type => $val) {

                    foreach ($val as $method) {

                        if (! $this->form->isAllowedInput($method)) {
                            continue;
                        }

                        $inputName = $method;

                        $inputType = strtolower($type);
                        $column = ['name' => $inputName, 'type' => $inputType, 'length' => null, 'value' => null, 'comment' => ''];
                        $inputType = $this->deriveInputTypeFromHints($column);

                        if ($this->factory->hasBinding($inputType)) {
                            $relatedModel = $this->model->$method()->getModel();

                            $props = [];

                            if (!$this->hasProp($inputName, 'options')) {
                                $props['model'] = $relatedModel;
                            }

                            if ($type == 'BelongsTo') {
                                $inputName = $this->model->$method()->getForeignKeyName();
                            }

                            if ($type == 'HasMany' || $type == 'BelongsToMany' || $type == 'MorphToMany' || $type == 'MorphMany') {

                                if ($rel = $this->model->{$method}) {

                                    if (method_exists($rel, 'pluck')) {
                                        $props['value'] = $rel->pluck($this->model->getKeyName())->toArray();
                                    }
                                }
                            }

                            if (!$this->hasProp($inputName, 'type')) {
                                $props['type'] = $inputType;
                            }

                            $props = $this->mergeProps($inputName, $props);

                            if (! empty($required) && in_array($inputName, $required)) {
                                $props['required'] = true;
                            }

                            $input = $this->form->create($inputName, $props);
                            $input->convertFromModel($column, $this);
                            $this->form->add($input);
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    protected function buildAnnotationInputs()
    {
        $annotations = [];
        $mirror = new \ReflectionClass($this->model);

        foreach ($mirror->getMethods(\ReflectionProperty::IS_PUBLIC) as $method) {

            // This limits the loop to just the non inherited methods.
            if ($method->class == $mirror->getName()) {

                $methodName = $method->getName();

                if (preg_match('#^get(.+)Attribute$#', $methodName)) {
                    continue;
                }

                $comment = $method->getDocComment();

                if (! empty($comment)) {

                    if (preg_match('#@field\s+(.+)\s+(\{.+\})?#U', $comment, $matches)) {
                        $name = $methodName;
                        if (! $this->form->isAllowedInput($name)) {
                            continue;
                        }
                        $type = $matches[1];
                        $name = $this->model->$name()->getForeignKey();
                        $props = (! empty($matches[2]) && is_json($matches[2])) ? json_decode($matches[2], true) : [];
                        $props = $this->mergeProps($name, $props);
                        $this->add($name, $type, $props);
                        //$annotations[$name] = [$type, $props];
                    }
                }
            }
        }

        return $annotations;
    }

    /**
     * @param $fields
     * @param $model
     * @return array|null
     */
    protected function getIgnorableFields($fields, $model)
    {
        static $ignore = null;

        if (! is_null($ignore)) {
            return $ignore;
        }

        $ignore = [];

        $ignorable = [
            'getCreatedAtColumn',
            'getUpdatedAtColumn',
            'getDeletedAtColumn',
            'getLastUpdatedByColumn',
            'getUpdatedByColumn',
        ];

        foreach ($ignorable as $ig) {
            if (method_exists($model, $ig)) {
                $ignore[] = $model->$ig();
            }
        }

        foreach ($fields as $field) {
            if (! $this->form->isAllowedInput($field) || (! in_array($field, $ignore) && (! $model->isFillable($field) && $field != $model->getKeyName()))) {
                $ignore[] = $field;
            }
        }

        return $ignore;
    }

    /**
     * @return array
     */
    protected function getValues()
    {
        return array_merge($this->form->getValues(), $this->model->toArray());
    }
}