<?php

namespace Snap\Ui\Components\Bootstrap;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Grid extends UiComponent implements ArrayAccess, IteratorAggregate, Countable
{
    use CssClassesTrait;

    protected $view = 'component::bootstrap.grid';

    protected $data = [
        'object:rows' => 'Illuminate\Support\Collection',
        // 'cols' => 4,
        'class' => null,
        'role'  => null,
        'id'    => null,
    ];

    public function add($row, $key = null)
    {
        if (is_array($row)) {
            foreach ($row as $key => $val) {
                $context = $r ?? $this;
                $r = $context->add($val, $key);
            }
        } else {
            if (! $row instanceof Row) {
                $newRow = new Row();
                $newRow->add($row);
                $row = $newRow;
            }

            if (is_null($key)) {
                $key = count($this->data['rows']);
            }

            $this->data['rows'][$key] = $row;
        }

        return $row;
    }

    public function remove($key)
    {
        $key = is_array($key) ? $key : func_get_args();

        foreach ($key as $val) {
            unset($this->data['rows'][$val]);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Get an iterator for the items.
     *
     * @access  public
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data['rows']);
    }

    // --------------------------------------------------------------------

    /**
     * Determine if an element exists at an offset.
     *
     * @access  public
     * @param  mixed  $index
     * @return bool
     */
    public function offsetExists($index)
    {
        return $this->data['rows'][$index];
    }

    // --------------------------------------------------------------------

    /**
     * Get a row.
     *
     * @access  public
     * @param  mixed  $index
     * @return mixed
     */
    public function offsetGet($index)
    {
        return $this->data['rows'][$index];
    }

    // --------------------------------------------------------------------

    /**
     * Set the data for a row.
     *
     * @access  public
     * @param  mixed  $index
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($index, $value)
    {
        $this->data['rows'][$index] = $value;
    }

    // --------------------------------------------------------------------

    /**
     * Unset the data at a given offset.
     *
     * @access  public
     * @param  string  $index
     * @return void
     */
    public function offsetUnset($index)
    {
        unset($this->data['rows'][$index]);
    }

    // --------------------------------------------------------------------

    /**
     * Returns the number of rows for the table.
     *
     * @access  public
     * @return int
     */
    public function count()
    {
        return count($this->data['rows']);
    }
}