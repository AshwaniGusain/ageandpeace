<?php

namespace Snap\Form;

use Snap\Form\Contracts\FormElementInterface;
use Snap\Ui\UiComponent;

abstract class FormElement extends UiComponent implements FormElementInterface
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $key;

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $order;

    /**
     * @var
     */
    protected $group;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = Form::createKey($key);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        if ($id === false) {
            $this->id = null;
        } else {
            $this->id = Form::createId($id);
        }

        return $this;
    }

    /**
     * @return int|mixed|null|string
     */
    public function getType()
    {
        foreach (config('snap.forms.types') as $type => $class) {
            if (is_array($class) && isset($class['class']) && $class['class'] == get_class($this)) {
                return $type;
            } elseif ($class == get_class($this)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }
}