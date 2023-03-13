<?php

namespace Snap\DataTable;

class DataTableJsSorter implements DataTableJsSorterInterface
{
    /**
     * The URL used for AJAXing the data when sorting.
     *
     * @var string
     */
    protected $ajaxUrl;

    /**
     * The selector to be used for replacing the table's HTML.
     *
     * @var string
     */
    protected $replaceSelector = '.datatable';

    /**
     * The javascript sorting function name.
     *
     * @var string
     */
    protected $jsFuncName = 'sortDataTable';

    /**
     * The javascript sorting function code.
     *
     * @var string
     */
    protected $jsFunc;

    /**
     * Checks if the JS function has already been output to the page.
     *
     * @var string
     */
    protected $funcRendered = false;

    /**
     * Use built in javascript function?
     *
     * @var string
     */
    protected $useBuiltInJs = true;

    /**
     * Constructor.
     *
     * @access  public
     * @param   \Snap\DataTable\DataTableColumn $sortedColumn
     * @param   string $ajaxUrl
     * @param   string $replaceSelector
     * @return  void
     */
    public function __construct(DataTableColumn $sortedColumn = null, $ajaxUrl = null, $replaceSelector = null)
    {
        if ($sortedColumn) {
            $this->setSortedColumn($sortedColumn);
        }
        if ($ajaxUrl) {
            $this->setAjaxUrl($ajaxUrl);
        }
        if ($replaceSelector) {
            $this->setReplaceSelector($replaceSelector);
        }
    }

    /**
     * Sets the sorted column.
     *
     * @access  public
     * @param   \Snap\DataTable\DataTableColumn $sortedColumn
     * @return  $this
     */
    public function setSortedColumn($sortedColumn)
    {
        $this->sortedColumn = $sortedColumn;

        return $this;
    }

    /**
     * Returns the sorted column.
     *
     * @access  public
     * @return  \Snap\DataTable\DataTableColumn
     */
    public function getSortedColumn()
    {
        return $this->sortedColumn;
    }

    /**
     * Returns the ajax URL.
     *
     * @access  public
     * @return  string
     */
    public function getAjaxUrl()
    {
        return $this->ajaxUrl;
    }

    /**
     * Sets the ajax URL.
     *
     * @access  public
     * @return  $this
     */
    public function setAjaxUrl($ajaxUrl)
    {
        $this->ajaxUrl = $ajaxUrl;

        return $this;
    }

    /**
     * Returns the replace selector used for jQuery (e.g. ".data-table")
     *
     * @access  public
     * @return  string
     */
    public function getReplaceSelector()
    {
        return $this->replaceSelector;
    }

    /**
     * Sets the replace selector used for jQuery (e.g. ".data-table")
     *
     * @access  public
     * @return  $this
     */
    public function setReplaceSelector($replaceSelector)
    {
        $this->replaceSelector = $replaceSelector;

        return $this;
    }

    /**
     * Returns the name of the javascript function used for sorting.
     *
     * @access  public
     * @return  string
     */
    public function getJsFuncName()
    {
        return $this->jsFuncName;
    }

    /**
     * Sets the javascript function used for sorting.
     *
     * @access  public
     * @return  $this
     */
    public function setJsFuncName($name)
    {
        $this->jsFuncName = $name;

        return $this;
    }

    /**
     * Returns the javascript function code for sorting.
     *
     * @access  public
     * @return  $this
     */
    public function getJsFunc()
    {
        return $this->jsFunc;
    }

    /**
     * Sets the javascript function code used for sorting.
     *
     * @access  public
     * @return  $this
     */
    public function setJsFunc($func, $name = null)
    {
        $this->jsFunc = $func;
        if (! empty($name)) {
            $this->jsFuncName = $this->setJsFuncName($name);
        }

        return $this;
    }

    /**
     * Sets whether to use the built in javascript function for sorting or not.
     *
     * @access  public
     * @return  $this
     */
    public function useBuiltIn($bool)
    {
        $this->useBuiltInJs = $bool;

        return $this;
    }

    /**
     * Renders the HTML and Javascript code needed for sorting
     *
     * @access  protected
     * @return  string
     */
    public function render()
    {
        $str = '';

        $str .= $this->jsSorting();
        $str .= $this->hiddenParams();

        return $str;
    }

    /**
     * Magic method for rendering just calling the render method
     *
     * @access  protected
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Returns the javascript function code.
     *
     * @access  protected
     * @return  string
     */
    protected function jsSorting()
    {
        $str = '';
        if (! $this->funcRendered) {
            if (! empty($this->jsFunc)) {
                $str = $this->getJsFunc();
            } elseif ($this->useBuiltInJs) {
                $str = $this->builtInJsSortingFunc();
            }

            $this->funcRendered = true;
        }

        return $str;
    }

    protected function builtInJsSortingFunc()
    {
        $func = '
			<script>
			function '.$this->getJsFuncName().'(elem){
				var $elem = $(elem);
				var $container = $elem.closest("'.$this->getReplaceSelector().'").parent();
				var col = $elem.data("column");
				var order = ($elem.hasClass("asc")) ? "desc" : "asc";
				$elem.parent().find(".active").removeClass("active");
				$elem.addClass("active");
				$container.find(".data-table-order").val(order);
				$container.find(".data-table-col").val(col);
				var url = $container.find(".data-table-ajax").val();
				var params = { col: col, order: order }
				$.get(url, params, function(html){
					$container.html(html);
					$elem.removeClass("asc desc").addClass(order + " active");
				})
			}</script>';

        return $func;
    }

    /**
     * Returns the hidden input fields used for passing information back to the server.
     *
     * @access  protected
     * @return  string
     */
    protected function hiddenParams()
    {
        $str = '';

        $sortedColumn = $this->getSortedColumn();
        if ($sortedColumn) {
            $str .= '<input name="order" type="hidden" value="'.$sortedColumn->getSortDirection().'" class="data-table-order">';
            $str .= '<input name="col" type="hidden" value="'.$sortedColumn->getKey().'" class="data-table-col">';
        }

        if (! empty($this->ajaxUrl)) {
            $str .= '<input name="ajax_url" type="hidden" value="'.$this->getAjaxUrl().'" class="data-table-ajax">';
        }

        return $str;
    }
}
