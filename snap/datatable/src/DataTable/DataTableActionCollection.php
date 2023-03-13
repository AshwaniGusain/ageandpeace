<?php

namespace Snap\DataTable;

class DataTableActionCollection implements \IteratorAggregate, \Countable
{
    /**
     * An array of DataTableAction objects
     *
     * @var string
     */
    protected $actions = [];

    /**
     * The delimiter to separate the actions in the action column.
     *
     * @var string
     */
    protected $delimiter = ' ';

    /**
     * Constructor.
     *
     * @access  public
     * @param   array $actions
     * @return  void
     */
    public function __construct($actions = [])
    {
        if (! empty($this->actions)) {
            $this->add($actions);
        }
    }

    /**
     * Adds DataTableAction object to the collection.
     *
     * @access  public
     * @param   mixed $action
     * @param   string $label
     * @param   array $props
     * @return  $this
     */
    public function add($action, $label = null, $props = [])
    {

        if ($action instanceof DataTableAction) {
            $this->actions[] = $action;
        } else {
            $this->actions[] = new DataTableAction($action, $label, $props);
        }

        return $this;
    }

    /**
     * Adds multiple DataTableAction objects to the collection.
     *
     * @access  public
     * @param   mixed $action
     * @param   string $label
     * @param   array $props
     * @return  $this
     */
    public function addMultiple($actions)
    {
        foreach ($actions as $key => $action) {
            $this->add($action);
        }

        return $this;
    }

    /**
     * Returns a DataTableAction object.
     *
     * @access  public
     * @param   string $key
     * @return  Snap\DataTable\DataTableAction
     */
    public function get($key)
    {
        return $this->actions[$key];
    }

    /**
     * Removes a DataTableAction object.
     *
     * @access  public
     * @param   string|array $key
     * @return  $this
     */
    public function remove($key)
    {
        if (is_array($key)) {
            foreach ($key as $action) {
                $this->remove($action);
            }
        } else {
            unset($this->actions[$key]);
        }

        return $this;
    }

    /**
     * Returns the number of DataTableAction objects.
     *
     * @access  public
     * @return  int
     */
    public function count()
    {
        return count($this->actions);
    }

    /**
     * Renders the actions for the action column.
     *
     * @access  public
     * @param   array $values
     * @return  string
     */
    public function render($values)
    {
        $return = [];
        foreach ($this->actions as $action) {
            $return[] = $action->render($values);
        }
        $output = implode($this->getDelimiter(), $return);

        return $output;
    }

    /**
     * Returns the delimiter.
     *
     * @access  public
     * @return  string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Sets the delimiter.
     *
     * @access  public
     * @return  $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Required definition of interface IteratorAggregate
     *
     * @access  public
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->actions);
    }
}
