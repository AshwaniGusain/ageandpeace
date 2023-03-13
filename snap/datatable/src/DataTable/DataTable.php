<?php

namespace Snap\DataTable;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;
use IteratorAggregate;
use Snap\Support\Contracts\ToString;

/*
|--------------------------------------------------------------------------
| EXAMPLE:
|--------------------------------------------------------------------------

$myModel = new MyModel;
$table = new DataTable();
echo $table->load($myModel->all())
    ->addIgnored('id')
    ->addAction('edit/{id}', 'EDIT')
    ->addAction(function($values){
        return '<a href="delete/'.$values['id'].'">DELETE</a>';
    })
    ->render();
*/

class DataTable implements ToString, Htmlable, ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Global formatters that will be run on any DataTable instance.
     *
     * @var []
     */
    protected static $globalFormatters = [];

    /**
     * The CSS class used for the table HTML element.
     *
     * @var string
     */
    protected $class = 'table table-striped datatable';

    /**
     * The ID used for the table HTML element.
     *
     * @var string
     */
    protected $id = null;

    /**
     * The tables HTML attributes.
     *
     * @var string
     */
    protected $attrs = [];

    /**
     * An array of column key values to be ignored when rendering.
     *
     * @var array
     */
    protected $ignored = ['id'];

    /**
     * An array of columns that should not be visible.
     *
     * @var array
     */
    protected $invisible = [];

    /**
     * A collection of columns (DataTableColumn objects).
     *
     * @var \Snap\DataTable\DataTableColumnCollection
     */
    protected $columns = null;

    /**
     * An array or collection of data for rendering the table.
     *
     * @var array
     */
    protected $data = [];

    /**
     * A collection of actions (DataTableAction objects).
     *
     * @var \Snap\DataTable\DataTableActionCollection
     */
    protected $actions = null;

    /**
     * The column key value to be used for rendering actions.
     *
     * @var string
     */
    protected $actionColumnKey = 'actions';

    /**
     * The column title to be used for rendering actions.
     *
     * @var string
     */
    protected $actionColumnTitle = 'Actions';

    /**
     * The column position for the actions.
     *
     * @var string
     */
    protected $actionsPosition = 'last';

    /**
     * The column key used when rendering the column (e.g <tr class="row_{id}... ).
     *
     * @var string
     */
    protected $rowIdPrefix = 'table-row-';

    /**
     * The column class prefix used when rendering the column (e.g <td class="col_{id}... ).
     *
     * @var string
     */
    protected $columnClassPrefix = 'table-col-';

    /**
     * The column class used when rendering the column (e.g <td class="col... ).
     *
     * @var string
     */
    protected $columnClass = 'table-col';

    /**
     * The CSS class used to indicate the first column.
     *
     * @var string
     */
    protected $firstColumnClass = 'first';

    /**
     * The CSS class used to indicate the last column.
     *
     * @var string
     */
    protected $lastColumnClass = 'last';

    /**
     * The CSS class used to indicate the currently sorted column.
     *
     * @var string
     */
    protected $columnSortClass = 'active';

    /**
     * The sorting direction CSS class used when rendering the table headers to indicate that it's sorting direction of "asc".
     *
     * @var string
     */
    protected $columnAscClass = 'asc';

    /**
     * The sorting direction CSS class used when rendering the table headers to indicate that it's sorting direction of "desc".
     *
     * @var string
     */
    protected $columnDescClass = 'desc';

    /**
     * The sortable CSS class used when rendering the table headers to indicate that it's sortable.
     *
     * @var string
     */
    protected $sortableClass = 'sortable';

    /**
     * The column that is currently used for sorting.
     *
     * @var \Snap\DataTable\DataTableColumn
     */
    protected $sortedColumn = null;

    /**
     * The message to display if no data exists.
     *
     * @var string
     */
    protected $emptyDataMessage = null;

    /**
     * Formatters that can be run on multiple columns based on a data's value.
     *
     * @var []
     */
    protected $formatters = [];

    /**
     * Constructor that can inject DataTableColumnCollection, DataTableActionCollection and DataTableJsSorter objects if you'd like.
     *
     * @access  public
     * @param \Snap\DataTable\DataTableColumnCollection $columns
     * @param \Snap\DataTable\DataTableActionCollection $actions
     */
    public function __construct(
        DataTableColumnCollection $columns = null,
        DataTableActionCollection $actions = null,
        Translator $translator = null
    ) {
        $this->columns = $columns ?: new DataTableColumnCollection([], app('translator'));
        $this->actions = $actions ?: new DataTableActionCollection();
        $this->setId(uniqid('datatable_'));
    }

    /**
     * Create a DataTable instance.
     *
     * @param   mixed $data An array of data, JSON, or an instance of Illuminate\Database\Query\Builder
     * @param   array $columns Specifies which columns of the data to be displayed. By default, it will display all of them
     * @param   bool $isJSON If set to true, it will run json_decode on the data being loaded.
     * @return \Snap\DataTable\DataTable
     */
    public static function make($data = null, $columns = [], $isJSON = false)
    {
        $table = new static(new DataTableColumnCollection([], app('translator')), new DataTableActionCollection());
        if ($data) {
            $table->load($data, $columns, $isJSON);
        }

        return $table;
    }

    /**
     * Adds a global formatter.
     *
     * @access  public
     * @param   array  An array of formatter classes as keys and values of arrays of columns
     * @return  $this
     */
    public static function addGlobalFormatters($formatters)
    {
        foreach ($formatters as $key => $val) {
            static::addGlobalFormatter($key, $val);
        }

        return new static;
    }

    /**
     * Adds a global formatter.
     *
     * @access  public
     * @param   mixed  A formatter to associate with a particular column only (optional)
     * @param   string  The column key OR a closure function that can apply to multiple columns based on the data
     * @return  $this
     */
    public static function addGlobalFormatter($formatter, $keys = null)
    {
        static::$globalFormatters[] = [$formatter, $keys];

        return new static;
    }

    /**
     * Loads the data for the table to be displayed.
     *
     * @access  public
     * @param   mixed $data An array of data, JSON, or an instance of Illuminate\Database\Query\Builder
     * @param   array $columns Specifies which columns of the data to be displayed. By default, it will display all of them
     * @param   bool $isJSON If set to true, it will run json_decode on the data being loaded.
     * @return  $this
     */
    public function load($data, $columns = [], $isJSON = false)
    {
        // Start fresh for each load.
        $this->removeData();

        // If it's a \Illuminate\Database\Query\Builder, then we will run the "get" method to get the Collection object.
        if ($data instanceof Builder) {
            $data = $data->get();
            // Check if the object has a method of getCollection, such as Pagainator objects
        } elseif (is_object($data) && method_exists($data, 'getCollection')) {
            $data = $data->getCollection();
        }

        // If isJSON == true, then we will run json_decode on it.
        if ($isJSON) {
            $data = json_decode($data, true);
        }

        // No data, then just return.
        if (empty($data)) {
            return $this;
        }

        // If no columns are specified, we will grab the first row and determine the columns to display.
        if (empty($columns)) {

            $first = [];

            // If is a Collection we will use the method "first" to grab the columns from the first row
            if ($data instanceof Collection) {
                $first = $data->first();
                // Otherwise, we will just grab the first row of the array.
            } elseif (is_array($data)) {
                $first = current($data);
            }

            // If it's a model, then we use toArray to get the column names.
            if (! empty($first) && is_object($first) && method_exists($first, 'toArray')) {
                $columns = $first->toArray();
            } else {
                $columns = (array) $first;
            }

            $columns = array_keys($columns);
        }

        // Finally we loop through the columns and add them.
        foreach ($columns as $key => $column) {

            if (is_int($key)) {
                $this->addColumn($column);
            } else {
                $this->addColumn($key, $column);
            }

            // Check if the column is ignored and if so, add them to the ignored array.
            // Ignored columns are not rendered.
            if ($this->isIgnored($column)) {
                $this->addIgnored($column);
            }

            // Check if the column is invisible and if so, add them to the invisible array.
            // Invisible columns are rendered but with a CSS display property of none.
            if ($this->isInvisible($column)) {
                $this->addInvisible($column);
            }
        }

        $this->setData($data);

        return $this;
    }

    /**
     * Removes a row or row and index data value.
     *
     * @access  public
     * @param   int $row The row index
     * @param   mixed $column Can be either the column key (string) or it's index (int)
     * @return  $this
     */
    public function removeData($row = null, $column = null)
    {
        if (isset($row)) {
            if (isset($column)) {
                unset($this->data[$row][$column]);
            } else {
                unset($this->data[$row]);
            }
        } else {
            $this->data = [];
        }

        return $this;
    }

    /**
     * Adds multiple columns to the table.
     *
     * @access  public
     * @param   string $column The data key for the column (e.g. id, title, name)
     * @param   string $title The friendly name that will be used in the head
     * @param   int $index The columns index to apply the column to
     * @param   bool $sortable Determines whether the column is sortable or not
     * @return  $this
     */
    public function addColumn($column, $title = null, $index = null, $sortable = true)
    {
        if (is_object($column) AND is_numeric($title)) {
            $this->columns->addAt($title, $column, $sortable);
        } elseif (isset($index)) {
            $this->columns->addAt($index, $column, $title, $sortable);
        } else {
            $this->columns->add($column, $title, $sortable);
        }

        return $this;
    }

    /**
     * Returns a boolean value as to whether a particular column's data is ignored or not during rendering.
     *
     * @access  public
     * @param   string $key A string value that represents the key value of the column (e.g. 'title').
     * @return  bool
     */
    public function isIgnored($key)
    {
        return in_array($key, $this->ignored);
    }

    /**
     * Sets a particular column of data to be ignored in the rendering but may be helpful for things like passing row ID values to an action's link.
     *
     * @access  public
     * @param   mixed $ignored Can be an array or string value that represents the key value of the column (e.g. ['id', 'deleted_at']).
     * @return  $this
     */
    public function addIgnored($ignored)
    {
        $ignored = (array) $ignored;

        foreach ($ignored as $i) {
            $this->ignored[$i] = $i;

            $column = $this->columns->get($i);

            if ($column) {
                $column->setIgnored(true);
            }
        }

        return $this;
    }

    /**
     * Returns a boolean value as to whether a particular column's data is ignored or not during rendering.
     *
     * @access  public
     * @param   string $key A string value that represents the key value of the column (e.g. 'title').
     * @return  bool
     */
    public function isInvisible($key)
    {
        return in_array($key, $this->invisible);
    }

    /**
     * Sets a particular column of data to be invisible. Can be helpful if you want to have the ability to
     * toggle columns on or off with javascript.
     *
     * @access  public
     * @param   mixed $invisible Can be an array or string value that represents the key value of the column.
     * @return  $this
     */
    public function addInvisible($invisible)
    {
        $invisible = (array) $invisible;

        foreach ($invisible as $i) {
            $this->invisible[$i] = $i;

            $column = $this->columns->get($i);

            if ($column) {
                $column->setVisible(false);
            }
        }

        return $this;
    }

    /**
     * Appends data
     *
     * @access  public
     * @param   mixed $data An array (or Collection)
     * @return  $this
     */
    public function appendData($data)
    {
        foreach ($data as $d) {
            $this->data[] = $d;
        }

        return $this;
    }

    /**
     * Sets the headers for the datatable.
     *
     * @access  public
     * @param   mixed $key Can be the name of the column or the index
     * @param   string $title The title to associate with the header. If no value is provided, then it will be a ucfirst version of the $key (optional)
     * @return  $this
     */
    public function setHeaders($key, $title = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $t) {
                $this->setHeader($k, $t);
            }
        } else {
            $this->setHeader($key, $title);
        }

        return $this;
    }

    /**
     * Sets a single headers for the datatable.
     *
     * @access  public
     * @param   mixed $key Can be the name of the column or the index
     * @param   string $title The title to associate with the header. If no value is provided, then it will be a ucfirst version of the $key (optional)
     * @return  $this
     */
    public function setHeader($key, $title = null)
    {
        $this->getColumn($key)->setTitle($title);

        return $this;
    }

    /**
     * Returns a single DataTableColumn object.
     *
     * @access  public
     * @param   mixed $column Can be the name of the column or the index
     * @return  \Snap\DataTable\DataTableColumn
     */
    public function getColumn($column)
    {
        return $this->columns->get($column);
    }

    /**
     * Returns the header information.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->columns->keys();
    }

    /**
     * Adds multiple columns to the table.
     *
     * @access  public
     * @param   array $columns An array (or Collection) or columns
     * @return  $this
     */
    public function addColumns($columns)
    {
        $this->columns->addMultiple($columns);

        return $this;
    }

    /**
     * Removes a columns from the table.
     *
     * @access  public
     * @param   string|int  The column key or index to remove
     * @return  $this
     */
    public function removeColumn($column)
    {
        $this->columns->remove($column);

        return $this;
    }

    /**
     * Returns an array of the column key values to be ignored and not displayed (e.g. ['id', 'deleted_at']).
     *
     * @access  public
     * @return  array
     */
    public function getIgnored()
    {
        return $this->ignored;
    }

    /**
     * Removes a particular column of data from being ignored.
     *
     * @access  public
     * @param   mixed $ignored Can be an array or string value that represents the key value of the column (e.g. ['id', 'deleted_at']).
     * @return  $this
     */
    public function removeIgnored($ignored)
    {
        $ignored = (array) $ignored;

        foreach ($ignored as $i) {
            if (isset($this->ignored[$i])) {
                unset($this->ignored[$i]);
            }

            $column = $this->columns->get($i);

            if ($column) {
                $column->setIgnored(false);
            }
        }

        return $this;
    }

    /**
     * Returns an array of the column key values to be invisible (e.g. CSS propert style="display: none").
     *
     * @access  public
     * @return  array
     */
    public function getInvisible()
    {
        return $this->invisible;
    }

    /**
     * Removes a particular column of data from being invisible.
     *
     * @access  public
     * @param   mixed $invisible Can be an array or string value that represents the key value of the column.
     * @return  $this
     */
    public function removeInvisible($invisible)
    {
        $invisible = (array) $invisible;

        foreach ($invisible as $i) {
            if (isset($this->invisible[$i])) {
                unset($this->invisible[$i]);
            }

            $column = $this->columns->get($i);

            if ($column) {
                $column->setVisible(true);
            }
        }

        return $this;
    }

    /**
     * Sets an action to be displayed in the Action column (e.g. EDIT, DELETE, VIEW ... etc).
     *
     * @access  public
     * @param   string $action The URL action on the link
     * @param   string $label The text to be displayed for the action
     * @param   array $attrs The link attributes for the action
     * @return  $this
     */
    public function addAction($action, $label = null, $attrs = [])
    {
        $this->actions->add($action, $label, $attrs);

        return $this;
    }

    /**
     * Removes an action from being displayed in the Action column (e.g. EDIT, DELETE, VIEW ... etc).
     *
     * @access  public
     * @param   int $key The index of the action
     * @return  $this
     */
    public function removeAction($key)
    {
        $this->actions->remove($key);

        return $this;
    }

    /**
     * Adds a formatter for the column.
     *
     * @access  public
     * @param   array  An array of formatter classes as keys and values of arrays of columns
     * @return  $this
     */
    public function addFormatters($formatters)
    {
        foreach ($formatters as $key => $val) {
            $this->addFormatter($key, $val);
        }

        return $this;
    }

    /**
     * Adds a formatter for the column.
     *
     * @access  public
     * @param   mixed  A formatter to associate with a particular column only (optional)
     * @param   string  The column key OR a closure function that can apply to multiple columns based on the data
     * @return  $this
     */
    public function addFormatter($formatter, $keys = null)
    {
        $this->formatters[] = [$formatter, $keys];

        return $this;
    }

    /**
     * Returns the sortedColumn.
     *
     * @access  public
     * @return  \Snap\DataTable\DataTableColumn
     */
    public function getSortedColumn()
    {
        return $this->sortedColumn;
    }

    /**
     * Sets the sortedColumn.
     *
     * @access  public
     * @param   string  The column key to sort by
     * @param   string  The direction to sort (either "asc" or "desc"... optional)
     * @return  $this
     */
    public function setSortedColumn($sortedColumn, $direction = 'asc')
    {
        $column = $this->columns->get($sortedColumn);
        if ($column) {
            $column->setSorted(true, $direction);
        }

        // reset any columns previously set back to their normal sorting state
        if (isset($this->sortedColumn)) {
            $this->sortedColumn->setSorted(false, 'asc');
        }
        $this->sortedColumn = $column;

        return $this;
    }

    /**
     * Helper method to determine if the column is clickable
     *
     * @access  public
     * @param   \Snap\DataTable\DataTableColumn
     * @return  boolean
     */
    public function isSortableColumn($column)
    {
        return $column->isSortable() && $this->hasJsSorting() && $column->getKey() != $this->getActionColumnKey();
    }

    /**
     * Returns the action columns column key (e.g. "actions").
     *
     * @access  public
     * @return  string
     */
    public function getActionColumnKey()
    {
        return $this->actionColumnKey;
    }

    /**
     * Sets the action columns column key (e.g. "actions").
     *
     * @access  public
     * @param   mixed $actionColumnKey The column to be used to display the actions. Can be "first", "last" or a string/integer for the column position.
     * @return  $this
     */
    public function setActionColumnKey($actionColumnKey)
    {
        $this->actionColumnKey = $actionColumnKey;

        return $this;
    }

    /**
     * Sets one or more table HTML attributes
     *
     * @access  public
     * @return  $this
     */
    public function setAttributes($key, $val = null)
    {
        if (is_array($key)) {
            $this->attrs = $key;
        } else {
            $this->attrs[$key] = $val;
        }

        return $this;
    }

    /**
     * Alias to render.
     *
     * @access  public
     * @return  string
     */
    public function toHtml()
    {
        return $this->render();
    }

    /**
     * Renders just the HTML for the table without any container or javascript initialization...
     *
     * @access  public
     * @return  string
     */
    public function render()
    {
        // First check if there is any data.
        if (! $this->hasData()) {
            // If no data and an emptyDataMessage value exists, display it
            if (! empty($this->getEmptyDataMessage())) {
                return $this->getEmptyDataMessage();
            } else {
                return '';
            }
        }

        $this->bindFormatters();

        // Set the action column's position.
        if ($this->getActions()->count() > 0) {
            $this->addColumn($this->getActionColumnKey(), $this->getActionColumnTitle(), $this->getActionsPosition());
        }

        // Set up table HTML.
        $columns = $this->getColumns();
        $html = "<table".$this->renderAttributes($this->getAttributes()).">";
        $html .= $this->renderHead($columns);
        $html .= $this->renderBody($columns);
        $html .= "</table>";

        return $html;
    }

    /**
     * Returns the HTML for the head of the table.
     *
     * @access  protected
     * @param \Snap\DataTable\DataTableColumnCollection
     * @return  string
     */
    protected function renderHead($columns) {
        $html = "<thead>";
        $html .= "<tr>";

        // Loop through the columns to create the TH headings.

        foreach ($columns as $column) {
            // Check a columns visiblity to determine whether to render it or not.
            if (! $column->isIgnored()) {
                if ($column->getKey() != $this->getActionColumnKey()) {
                    $thClasses = $this->getThClasses($column);
                    $html .= "<th class=\"{$thClasses}\" data-column=\"{$column->getKey()}\"";
                } else {
                    $html .= "<th data-column=\"{$column->getKey()}\"";
                }

                if (! $column->isVisible()) {
                    $html .= " style=\"display: none\"";
                }

                $html .= ">{$column->getTitle()}</th>";
            }
        }

        $html .= "</tr>";
        $html .= "</thead>";

        return $html;
    }

    /**
     * Returns the HTML for the body of the table.
     *
     * @access  protected
     * @param \Snap\DataTable\DataTableColumnCollection
     * @return  string
     */
    protected function renderBody($columns)
    {
        $html = "<tbody>";

        // Now loop through the columns to get output the rows.
        for ($i = 0; $i < $this->getRowCount(); $i++) {
            $html .= "<tr id=\"".$this->rowIdPrefix.$i."\">";
            foreach ($columns as $column) {
                // Check a columns visiblity to determine whether to render it or not.
                if (! $column->isIgnored()) {
                    $key = $column->getKey();

                    // Put all the TD classes into an array in which we can implode later for rendering.
                    if ($column->getKey() != $this->getActionColumnKey()) {
                        $classes = $this->getTdClasses($column);
                        $html .= "<td class=\"{$classes}\"";
                    } else {
                        $html .= "<td class=\"{$column->getKey()}\"";
                    }

                    if (! $column->isVisible()) {
                        $html .= " style=\"display: none\"";
                    }
                    $html .= ">";

                    // Render the action column.
                    if ($key == $this->getActionColumnKey()) {
                        $rowData = $this->getData($i);
                        $html .= $this->actions->render($rowData);
                    } // Render the data for the table.
                    else {
                        $columnData = $this->getData($i, $column->getKey());

                        if ($column->hasFormatter()) {
                            $columnData = $column->runFormatters($columnData, $this->data[$i], $column->getKey());
                        }

                        // Be sure the data is renderable to a string.
                        if (is_string($columnData) || is_numeric($columnData) || (is_object($columnData) && method_exists($columnData, '__toString'))) {
                            $html .= (string) $columnData;
                        }
                    }
                    $html .= "</td>";
                }
            }

            $html .= "</tr>";
        }

        $html .= "</tbody>";

        return $html;
    }

    /**
     * Returns the whether the table has data or not.
     *
     * @access  public
     * @return  bool
     */
    public function hasData()
    {
        return ! empty($this->getRowCount());
    }

    /**
     * Returns the empty data message.
     *
     * @access  public
     * @return  string
     */
    public function getEmptyDataMessage()
    {
        return $this->emptyDataMessage;
    }

    /**
     * Sets the empty data message.
     *
     * @access  public
     * @param   string
     * @return  $this
     */
    public function setEmptyDataMessage($str)
    {
        $this->emptyDataMessage = $str;

        return $this;
    }

    /**
     * Binds the set formatters to the columns during render.
     *
     * return null
     */
    protected function bindFormatters()
    {
        foreach ([static::$globalFormatters, $this->formatters] as $formatters) {

            foreach ($formatters as $f) {
                $formatter = $f[0];
                $keys = $f[1];

                if (is_int($formatter)) {
                    $formatter = $keys;
                    $keys = $this->columns->keys();

                } elseif (empty($keys) || $keys == '*') {
                    $keys = $this->columns->keys();
                }

                $keys = (array) $keys;
                foreach ($keys as $key) {
                    $column = $this->columns->get($key);
                    if ($column) {
                        $column->addFormatter($formatter);
                    }
                }
            }
        }
    }

    /**
     * Returns a collection of Snap\DataTable\DataTableAction objects for the table.
     * Actions are normally a link to something like an edit page to manipulate the data tables data.
     *
     * @access  public
     * @return  \Snap\DataTable\DataTableActionCollection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Returns the action columns column key (e.g. "actions").
     *
     * @access  public
     * @return  string
     */
    public function getActionColumnTitle()
    {
        return $this->actionColumnTitle;
    }

    /**
     * Sets the action columns column key (e.g. "actions").
     *
     * @access  public
     * @param   mixed $actionColumnTitle The column to be used to display the actions. Can be "first", "last" or a string/integer for the column position.
     * @return  $this
     */
    public function setActionColumnTitle($actionColumnTitle)
    {
        $this->actionColumnTitle = $actionColumnTitle;

        return $this;
    }

    /**
     * Returns the position of where the actions shoule be rendered.
     *
     * @access  public
     * @return  mixed
     */
    public function getActionsPosition()
    {
        return $this->actionsPosition;
    }

    /**
     * Sets the position of where the actions shoule be rendered.
     *
     * @access  public
     * @param   mixed $position The column to be used to display the actions. Can be "first", "last" or a string/integer for the column position.
     * @return  $this
     */
    public function setActionsPosition($position)
    {
        if (strtolower($position) == 'first') {
            $position = 0;
        }
        $this->actionsPosition = $position;

        return $this;
    }

    public function renderAttributes($attributes)
    {
        $atts = '';

        if (empty($attributes)) {
            return $atts;
        }

        if (is_string($attributes)) {
            return ' '.$attributes;
        }

        $attributes = (array) $attributes;

        foreach ($attributes as $key => $val) {
            $atts .= ' '.$key.'="'.htmlspecialchars($val, ENT_QUOTES, 'utf-8').'"';
        }

        return rtrim($atts, ',');
    }

    /**
     * Returns an array of table HTML attributes
     *
     * @access  public
     * @return  array
     */
    public function getAttributes($key = null)
    {
        if ($id = $this->getId()) {
            $this->attrs['id'] = $this->getId();
        }
        if ($class = $this->getClass()) {
            $this->attrs['class'] = $class;
        }

        if (isset($key)) {
            if (array_key_exists($key, $this->attrs)) {
                return $this->attrs[$key];
            }

            return null;
        }

        return $this->attrs;
    }

    /**
     * Returns an instance of DataTableColumnCollection which contains all the DataTableColumn objects.
     *
     * @access  public
     * @return  \Snap\DataTable\DataTableColumnCollection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Helper method to get the classes for a TH
     *
     * @access  public
     * @param   Snap\DataTable\DataTableColumn
     * @return  array
     */
    public function getThClasses($column, $asArray = false)
    {
        $classes = [];

        // Put all the TH classes into an array in which we can implode later for rendering.
        if ($column->isSortable()) {
            $classes[] = $this->getSortableClass();
        }

        if ($column->isSorted()) {
            $classes[] = $this->getColumnSortClass();
            $classes[] = ($column->getSortDirection() == 'desc') ? $this->getColumnDescClass() : $this->getColumnAscClass();
        }

        if ($asArray) {
            return $classes;
        }

        $classes = implode(' ', $classes);

        return $classes;
    }

    /**
     * Returns the total number of rows count.
     *
     * @access  public
     * @return  int
     */
    public function getRowCount()
    {
        return count($this->data);
    }

    /**
     * Helper method to get the classes for a TD
     *
     * @access  public
     * @param   Snap\DataTable\DataTableColumn
     * @return  array
     */
    public function getTdClasses($column, $asArray = false)
    {
        // Put all the TD classes into an array in which we can implode later for rendering.
        $classes = [
            $this->getColumnClass(),
            $this->getColumnClassPrefix().$column->getIndex(),
            str_replace(' ', '_', $this->getColumnClassPrefix().$column->getKey()),
        ];

        // Find the first column.
        $firstVisibleColumn = $this->columns->search(function ($column, $key) {
            if ($column->isVisible()) {
                return $column;
            }
        });

        // Set the first class.
        if ($this->firstVisibleColumn()->getIndex() == $column->getIndex()) {
            $classes[] = $this->getFirstColumnClass();
        } // Set last class before actions.
        elseif ($column->getIndex() == $this->columns->count() - 1) {
            $classes[] = $this->getLastColumnClass();
        }

        if ($asArray) {
            return $classes;
        }

        return implode(' ', $classes);
    }

    /**
     * Returns data information depending on what row and/or column index is passed to the method.
     * If no row or column index is passed, then it will simply return all the data.
     *
     * @access  public
     * @param   int $row The row index to retrieve (optional)
     * @param   int $column The column index to retrieve (optional)
     * @return  mixed either and array or collection
     */
    public function getData($row = null, $column = null)
    {
        // If the row is provided, we will return an array of DataTableColumn objects
        if (isset($row)) {
            // If the column is provided, then we'll dig down into the column level and return the DataTableColumn object.
            if (isset($column)) {
                $col = $this->getColumn($column);

                if (isset($col)) {
                    $r = $this->data[$row];

                    if (is_object($r) && ! $r instanceof ArrayAccess) {
                        $method = $col->getMethod();

                        return $this->data[$row]->$method;
                    } else {
                        // This is used to translate model relationships to array syntax.
                        $column = str_replace(':', '.', $column);

                        return array_get($this->data[$row], $column);
                        //return $this->data[$row][$column];
                    }
                }

                return null;
            } else {
                if (isset($this->data[$row])) {
                    // iterate over them to get a normalized set of data
                    $rowData = [];

                    foreach ($this->getColumns() as $column) {
                        $key = $column->getKey();

                        if ($key != $this->getActionColumnKey()) {
                            $rowData[$key] = $this->getData($row, $key);
                        }
                    }
                    $this->data[$row] = $rowData;

                    return $this->data[$row];
                }

                return null;
            }
        }

        return (is_object($this->data) && method_exists($this->data, 'toArray')) ? $this->data->toArray() : $this->data;
    }

    /**
     * Sets the data value. This does not set the headers to display like the load method
     *
     * @access  public
     * @param   mixed $data An array (or Collection)
     * @param   int $row The row index
     * @param   mixed $column Can be either the column key (string) or it's index (int)
     * @return  $this
     */
    public function setData($data, $row = null, $column = null)
    {
        if (isset($row)) {
            if (isset($column)) {
                $this->data[$row][$column] = $data;
            } else {
                $this->data[$row] = $data;
            }
        } else {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * Returns the ID on the table HTML element
     *
     * @access  public
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the ID on the table HTML element
     *
     * @access  public
     * @param   string $id The ID for HTML element
     * @return  $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the CSS class on the table HTML element
     *
     * @access  public
     * @return  string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the CSS class on the table HTML element
     *
     * @access  public
     * @param   string $class The CSS class name
     * @return  $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Returns the CSS class used to indicate a sortable column and gets placed in the TH element.
     *
     * @access  public
     * @return  string
     */
    public function getSortableClass()
    {
        return $this->sortableClass;
    }

    /**
     * Sets the CSS class used to indicate a sortable column which gets placed in the TH element.
     *
     * @access  public
     * @param   string  Either "asc" or "desc"
     * @return  $this
     */
    public function setSortableClass($sortableClass)
    {
        $this->sortableClass = $sortableClass;

        return $this;
    }

    /**
     * Returns the columnAscClass.
     *
     * @access  public
     * @return  string
     */
    public function getColumnSortClass()
    {
        return $this->columnSortClass;
    }

    /**
     * Sets the columnAscClass.
     *
     * @access  public
     * @param   string
     * @return  $this
     */
    public function setColumnSortClass($columnSortClass)
    {
        $this->columnSortClass = $columnSortClass;

        return $this;
    }

    /**
     * Returns the ColumnDescClass.
     *
     * @access  public
     * @return  string
     */
    public function getColumnDescClass()
    {
        return $this->columnDescClass;
    }

    /**
     * Sets the ColumnDescClass.
     *
     * @access  public
     * @param   string  The CSS class name to be used for "descending" order
     * @return  $this
     */
    public function setColumnDescClass($columnDescClass)
    {
        $this->columnDescClass = $columnDescClass;

        return $this;
    }

    /**
     * Returns the columnAscClass.
     *
     * @access  public
     * @return  string
     */
    public function getColumnAscClass()
    {
        return $this->columnAscClass;
    }

    /**
     * Sets the columnAscClass.
     *
     * @access  public
     * @param   string  The CSS class name to be used for "ascending" order
     * @return  $this
     */
    public function setColumnAscClass($columnAscClass)
    {
        $this->columnAscClass = $columnAscClass;

        return $this;
    }

    /**
     * Returns the CSS class to be used for the column (e.g. col).
     *
     * @access  public
     * @return  string
     */
    public function getColumnClass()
    {
        return $this->columnClass;
    }

    /**
     * Sets the CSS class prefix to be used for the column (e.g. col).
     *
     * @access  public
     * @param   string  The CSS class name
     * @return  $this
     */
    public function setColumnClass($columnClass)
    {
        $this->columnClass = $columnClass;

        return $this;
    }

    /**
     * Returns the CSS class prefix to be used for the column (e.g. col_{i}).
     *
     * @access  public
     * @return  string
     */
    public function getColumnClassPrefix()
    {
        return $this->columnClassPrefix;
    }

    /**
     * Sets the CSS class prefix to be used for the column (e.g. col_{i}).
     *
     * @access  public
     * @param   string  The CSS class name
     * @return  $this
     */
    public function setColumnClassPrefix($columnClassPrefix)
    {
        $this->columnClassPrefix = $columnClassPrefix;

        return $this;
    }

    /**
     * Helper method that returns the first visible column
     *
     * @access  public
     * @return  \Snap\DataTable\DataTableColumn
     */
    public function firstVisibleColumn()
    {
        return $this->columns->search(function ($column, $key) {
            if (! $column->isIgnored()) {
                return $column;
            }
        });
    }

    /**
     * Returns the first column's class.
     *
     * @access  public
     * @return  string
     */
    public function getFirstColumnClass()
    {
        return $this->firstColumnClass;
    }

    /**
     * Sets the CSS class name that will be used for the first column.
     *
     * @access  public
     * @param   string  The CSS class name
     * @return  $this
     */
    public function setFirstColumnClass($firstColumnClass)
    {
        $this->firstColumnClass = $firstColumnClass;

        return $this;
    }

    /**
     * Returns the last column's class.
     *
     * @access  public
     * @return  string
     */
    public function getLastColumnClass()
    {
        return $this->lastColumnClass;
    }

    /**
     * Sets the CSS class name that will be used for the last column.
     *
     * @access  public
     * @param   string  The CSS class name
     * @return  $this
     */
    public function setLastColumnClass($lastColumnClass)
    {
        $this->lastColumnClass = $lastColumnClass;

        return $this;
    }

    /**
     * Magic method to just call the render method of the object is needing to be converted to a string.
     *
     * @access  public
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get an iterator for the items.
     *
     * @access  public
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getData());
    }

    /**
     * Determine if an element exists at an offset.
     *
     * @access  public
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->getData($key) != null;
    }

    /**
     * Get a row.
     *
     * @access  public
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->getData($key);
    }

    /**
     * Set the data for a row.
     *
     * @access  public
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->setData($key, $value);
    }

    /**
     * Unset the data at a given offset.
     *
     * @access  public
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->removeData($key);
    }

    /**
     * Returns the number of rows for the table.
     *
     * @access  public
     * @return int
     */
    public function count()
    {
        return $this->getRowCount();
    }
}
