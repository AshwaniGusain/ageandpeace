<?php

namespace Snap\DataTable;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class DataTableColumnCollection implements IteratorAggregate, Countable
{
    /**
     * An array of Snap\DataTable\DataTableColumn;
     *
     * @var string
     */
    protected $items = [];

    /**
     * An array used for looking up a column based on the key.
     *
     * @var string
     */
    protected $lookup = [];

    /**
     * The translation object.
     *
     * @var string
     */
    protected $translator = null;

    /**
     * Constructor.
     *
     * @access  public
     * @param   mixed $items
     * @return  void
     */
    public function __construct($items = [], $translator = null)
    {
        if (! empty($this->items)) {
            $this->add($items);
        }

        if (is_null($translator)) {
            $translator = app('translator');
        }

        $this->translator = $translator;
    }

    /**
     * Adds a column to the collection.
     *
     * @access  public
     * @param   string $column
     * @param   string $title
     * @param   bool $sortable
     * @return  $this
     */
    public function add($column, $title = null, $sortable = true)
    {
        if (is_array($column)) {
            foreach ($column as $col) {
                $this->add($col);
            }
        } else {
            $index = count($this->lookup);
            $this->addAt($index, $column, $title, $sortable);
        }

        return $this;
    }

    /**
     * Adds a column to the collection at a certain index.
     *
     * @access  public
     * @param   int $index
     * @param   mixed $column
     * @param   string $title
     * @param   bool $sortable
     * @return  $this
     */
    public function addAt($index, $column, $title = null, $sortable = true)
    {
        // Shortcut to set to the last index.
        if (strtolower($index) === 'last') {
            $index = count($this->items);
        } elseif (strtolower($index) === 'first') {
            $index = -1;
        }

        if ($column instanceof DataTableColumn) {
            $column->setIndex($index);
            $this->items[$index] = $column;
        } elseif (is_string($column)) {
            if (is_null($title)) {
                $title = preg_split('#[\.:]#U', $column);
                $title = end($title);

                if ($this->translator->has('datatable::formatters.'.$column)) {
                    $title  = $this->translator->get('datatable::formatters.'.$column);
                } else {
                    $title = ucwords(str_replace('_', ' ', $title));
                }
            }

            $props = ['key' => $column, 'title' => $title, 'index' => $index, 'sortable' => true];
            if (is_array($title)) {
                $props = array_merge($props, $title);
            }
            $column = new DataTableColumn($props);
            $this->items[$index] = $column;
        }

        if (! isset($this->lookup[$column->getKey()])) {
            $this->lookup[$column->getKey()] = $index;
        }

        return $this;
    }

    /**
     * Removes one ore more DataTableColumn objecs from the collection
     *
     * @access  public
     * @param   array
     * @return  $this
     */
    public function remove($column)
    {
        if (is_array($column)) {
            foreach ($column as $col) {
                $this->remove($col);
            }
        } else {
            if (! is_numeric($column) && isset($this->lookup[$column])) {
                $index = $this->lookup[$column];
                unset($this->items[$index]);
                unset($this->lookup[$column]);
            } elseif (isset($this->items[$column])) {
                $col = $this->items[$column];
                unset($this->items[$column]);
                unset($this->lookup[$col->getKey()]);
            }
        }

        return $this;
    }

    /**
     * Adds multiple DataTableColumn objecs to the collection
     *
     * @access  public
     * @param   array $columns
     * @return  $this
     */
    public function addMultiple($columns)
    {
        foreach ($columns as $key => $column) {
            if (! is_int($key)) {
                $this->add($key, $column);
            } else {
                $this->add($column);
            }
        }

        return $this;
    }

    /**
     * Search the collection for a given value and return the corresponding key if successful.
     *
     * @param  mixed $value
     * @param  bool $strict
     * @return mixed
     */
    public function search($value, $strict = false)
    {
        if (is_string($value) && ! is_callable($value)) {
            return array_search($value, $this->items, $strict);
        }

        foreach ($this->items as $key => $item) {
            if (call_user_func($value, $item, $key)) {
                return $item;
            }
        }

        return false;
    }

    /**
     * Returns a column from the collection.
     *
     * @access  public
     * @param   string $key
     * @return  Snap\DataTable\DataTableColumn
     */
    public function get($key)
    {
        if (is_numeric($key)) {
            return $this->items[$key];
        } elseif (isset($this->lookup[$key])) {
            $k = $this->lookup[$key];

            return $this->items[$k];
        }

        return null;
    }

    /**
     * The column keys.
     *
     * @access  public
     * @return  array
     */
    public function keys()
    {
        return array_keys($this->lookup);
    }

    /**
     * Returns the number of columns for the table.
     *
     * @access  public
     * @return  int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Required definition of interface IteratorAggregate.
     *
     * @access  public
     * @param   bool $sortable
     * @return  $this
     */
    public function getIterator()
    {
        ksort($this->items);

        return new ArrayIterator($this->items);
    }
}
