<?php

namespace Snap\DataTable;

use Closure;

class DataTableAction
{
    /**
     * A URL or Closure representing the columns action.
     *
     * @var mixed
     */
    protected $action;

    /**
     * The label to be used for the action
     *
     * @var string
     */
    protected $label;

    /**
     * The attrs on the link for the action
     *
     * @var array
     */
    protected $attrs = [];

    /**
     * Constructor.
     *
     * @access  public
     * @param   mixed $action
     * @param   string $label
     * @return  void
     */
    public function __construct($action = null, $label = null, $attrs = null)
    {
        if (isset($action)) {
            $this->set($action, $label, $attrs);
        }
    }

    /**
     * Sets the action and optionally the label to be used for the rendering of the action.
     *
     * @access  public
     * @param   mixed $action
     * @param   string $label
     * @param   array $attrs
     * @return  $this
     */
    public function set($action, $label = '', $attrs = [])
    {
        $this->action = $action;
        $this->label = $label;
        $this->attrs = $attrs;

        return $this;
    }

    /**
     * Renders the action
     *
     * @access  public
     * @param   array $values an array of column values
     * @return  string
     */
    public function render($values)
    {
        if ($this->action instanceof Closure) {
            return call_user_func($this->action, $values);
        } else {
            $action = $this->action;
            foreach ($values as $key => $val) {
                if (! is_array($val) && ! is_object($val)) {
                    $val = (string) $val;
                    $action = str_replace('{'.$key.'}', $val, $action);
                }
            }
            $this->attrs['href'] = $action;

            return '<a'.\Snap\Support\Helpers\HtmlHelper::attrs($this->attrs).'>'.$this->label.'</a>';
        }
    }
}
