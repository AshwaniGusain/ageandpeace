<?php

namespace Snap\DataTable;

class DataTableColumn
{
    /**
     * The key value for a column (e.g. name).
     *
     * @var string
     */
    protected $key;

    /**
     * The title (label) of the column (e.g Name).
     *
     * @var string
     */
    protected $title;

    /**
     * Determines if this column is visible.
     *
     * @var bool
     */
    protected $visible = true;

    /**
     * Determines if this column is ignored.
     *
     * @var bool
     */
    protected $ignored = false;

    /**
     * The method used to return the column data.
     *
     * @var string
     */
    protected $method;

    /**
     * The index value of the column for the table.
     *
     * @var int
     */
    protected $index;

    /**
     * A formatter function to run before rendering the column value.
     *
     * @var array
     */
    protected $formatters = [];

    /**
     * Determines whether the column is sortable or not.
     *
     * @var boolean
     */
    protected $sortable = true;

    /**
     * Determinse whether the column is currently sorted or not.
     *
     * @var boolean
     */
    protected $sorted = false;

    /**
     * The direction in which the column is sorted (if it is currently the sorted column).
     *
     * @var boolean
     */
    protected $sortDirection = 'asc';

    /**
     * Constructor.
     *
     * @access  public
     * @param   array $props
     * @return  void
     */
    public function __construct($props = [])
    {
        if (! empty($props) && is_array($props)) {
            $this->assign($props);
        }
    }

    /**
     * Sets object properties
     *
     * @access public
     * @param $prop
     * @param null $value
     * @return \Snap\DataTable\DataTableColumn
     */
    public function assign($prop, $value = null)
    {
        // If an array, then we will loop through it and assign values
        if (is_array($prop)) {
            foreach ($prop as $key => $val) {
                $method = 'set'.ucfirst($key);

                // check first if a method exists
                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->throwError($key);
                }
            }
        } else {
            $method = 'set'.ucfirst($prop);

            // check first if a method exists
            if (method_exists($method)) {
                $this->$method($value);
            } else {
                $this->throwError($prop);
            }
        }

        return $this;
    }

    /**
     * Returns the key.
     *
     * @access  public
     * @return  string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @access  public
     * @param   string
     * @return  $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns the title.
     *
     * @access  public
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @access  public
     * @param   string $title
     * @return  $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Returns whether a column is visible.
     *
     * @access  public
     * @return  bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Sets visiblility of a column with display CSS property.
     *
     * @access  public
     * @param   bool $visible
     * @return  $this
     */
    public function setVisible($visible)
    {
        $this->visible = (bool) $visible;

        return $this;
    }

    /**
     * Returns whether a column is ignored.
     *
     * @access  public
     * @return  bool
     */
    public function isIgnored()
    {
        return $this->ignored;
    }

    /**
     * Sets whether a column is ignored completely from rendering.
     *
     * @access  public
     * @param   bool $ignored
     * @return  $this
     */
    public function setIgnored($ignored)
    {
        $this->ignored = (bool) $ignored;

        return $this;
    }

    /**
     * Returns the method name.
     *
     * @access  public
     * @return  string
     */
    public function getMethod()
    {
        return $this->key.ucfirst($this->method);
    }

    /**
     * Sets the method name.
     *
     * @access  public
     * @param   string $method
     * @return  $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Returns the index.
     *
     * @access  public
     * @return  int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Sets the index.
     *
     * @access  public
     * @param   int $index
     * @return  $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Determins whether the column has a formatter that needs to be applied for rendering.
     *
     * @access  public
     * @return  bool
     */
    public function hasFormatter($key = null)
    {
        if (isset($key)) {
            return ! empty($this->formatters[$key]);
        }

        return ! empty($this->formatters);
    }

    /**
     * Runs the formatter Closure.
     *
     * @access  public
     * @param   array $columnData
     * @param   array $rowData
     * @return  string
     */
    public function runFormatters($columnData, $rowData, $key)
    {
        foreach ($this->formatters as $formatter) {
            if (is_string($formatter)) {
                $parts = explode(',', $formatter);
                $formatter = array_shift($parts);
            }
            if (is_callable($formatter)) {
                $params = [$columnData, $rowData, $key];
                if (!empty($parts)) {
                    $params = array_merge($params, $parts);
                }
                $columnData = call_user_func_array($formatter, $params);
            }
        }

        return $columnData;
    }

    /**
     * Sets the formatter.
     *
     * @access  public
     * @param  $formatter (usually a Closure)
     * @return  $this
     */
    public function addFormatter($formatter)
    {
        $this->formatters[] = $formatter;

        return $this;
    }

    /**
     * Removes a formatter.
     *
     * @access  public
     * @param   int $formatter index
     * @return  $this
     */
    public function removeFormatter($index)
    {
        unset($this->formatters[$index]);

        return $this;
    }

    /**
     * Returns the sorted.
     *
     * @access  public
     * @return  bool
     */
    public function isSorted()
    {
        return $this->isSortable() && $this->sorted;
    }

    /**
     * Sets the column as being sorted.
     *
     * @access  public
     * @param   bool $sorted
     * @param   string $direction
     * @return  $this
     */
    public function setSorted($sorted, $direction = 'asc')
    {
        $this->sorted = (bool) $sorted;
        $this->setSortDirection($direction);

        return $this;
    }

    /**
     * Returns the sortable.
     *
     * @access  public
     * @return  bool
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * Sets whether the column is sortable or not.
     *
     * @access  public
     * @param   bool $sortable
     * @return  $this
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Returns the sort direction.
     *
     * @access  public
     * @return  string
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * Sets the sort direction.
     *
     * @access  public
     * @param   string $sortDirection
     * @return  $this
     */
    public function setSortDirection($sortDirection)
    {
        $this->sortDirection = $sortDirection;

        return $this;
    }

    /**
     * Helper mento to encode a strings HTML entities.
     *
     * @access    protected
     * @param    int    The number of tabs to create
     * @return    string
     */
    protected function throwError($prop)
    {
        throw new \InvalidArgumentException("Menu property {$prop} does not exist.");
    }
}
