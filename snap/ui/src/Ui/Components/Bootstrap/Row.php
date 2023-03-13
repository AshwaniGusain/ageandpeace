<?php

namespace Snap\Ui\Components\Bootstrap;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Row extends UiComponent implements ArrayAccess, IteratorAggregate, Countable
{
    use CssClassesTrait;

    protected $view = 'component::bootstrap.row';

    protected $data = [
        'object:cols' => 'Illuminate\Support\Collection',
        'class' => null,
    ];

    public function add($col, $key = null)
    {
        if (is_array($col)) {
            foreach ($col as $key => $val) {
                $this->add($val, $key);
            }
        } else {
            if (! $col instanceof Column) {
                $newColumn = new Column();
                $newColumn->setContent($col);
                $col = $newColumn;
            }

            if (is_null($key)) {
                $key = count($this->data['cols']);
            }
            $this->data['cols'][$key] = $col;
        }

        return $this;
    }

    public function remove($key)
    {
        $key = is_array($key) ? $key : func_get_args();

        foreach ($key as $val) {
            unset($this->data['cols'][$val]);
        }

        return $this;
    }

    protected function _render()
    {
        $colNum = count($this->cols);
        $class = 'col-md-'.(12 / $colNum);
        foreach ($this->cols as $key => $col) {
            if (empty($this->cols[$key]->class)) {
                $this->cols[$key]->setClass($class);
            }
        }

        return parent::_render();
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
        return new ArrayIterator($this->data['cols']);
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
        return $this->data['cols'][$index];
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
        return $this->data['cols'][$index];
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
        $this->data['cols'][$index] = $value;
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
        unset($this->data['cols'][$index]);
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
        return count($this->data['cols']);
    }
}