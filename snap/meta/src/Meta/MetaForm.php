<?php

namespace Snap\Meta;

abstract class MetaForm
{
    protected $form;
    protected $scope = 'meta[]';

    public function __construct()
    {
        $this->form = \Form::make()->useFormTags(false);
        $this->scope($this->scope);
    }

    public function getForm($values = [])
    {
        if ($values) {
            if ($this->scope) {
                $scope = str_replace(['[',']'], '', $this->scope);
                $newValues = [];
                foreach ($values as $key => $val) {
                    $newValues[$scope.'.'.$key] = $val;
                }
                $values = $newValues;
            }

            $this->form->withValues($values);
        }

        if (method_exists($this, 'inputs')) {
            $this->form->add($this->inputs());
        }

        return $this->form;
    }

    public function scope($scope)
    {
        $this->form->scope($scope);

        return $this;
    }

    abstract public function inputs();
}