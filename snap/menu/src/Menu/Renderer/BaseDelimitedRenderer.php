<?php

namespace Snap\Menu\Renderer;

abstract class BaseDelimitedRenderer extends BaseHtmlRenderer
{
    /**
     * The HTML to display between menu items.
     *
     * @var string
     */
    protected $delimiter = ' &gt; ';

    /**
     * The CSS class to use for the delimiter.
     *
     * @var string
     */
    protected $delimiterClass = 'delimiter';

    /**
     * The HTML element used surround the rendered menu items.
     *
     * @var string
     */
    protected $containerTag = '';

    /**
     * The HTML tag to use when rendering the item.
     *
     * @var string
     */
    protected $itemTag = '';

    /**
     * Returns the delimiter option.
     *
     * @access  protected
     * @return  string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Sets the delimiter option.
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
     * Returns the delimiter class option.
     *
     * @access  public
     * @return  string
     */
    public function getDelimiterClass()
    {
        return $this->delimiterClass;
    }

    /**
     * Sets the delimiter class option.
     *
     * @access  public
     * @return  $this
     */
    public function setDelimiterClass($delimiterClass)
    {
        $this->delimiterClass = $delimiterClass;

        return $this;
    }

    /**
     * Returns the container tag option.
     *
     * @access  public
     * @return  string
     */
    public function getContainerTag()
    {
        return $this->containerTag;
    }

    /**
     * Sets the container tag option.
     *
     * @access public
     * @param $containerTag
     * @return $this
     */
    public function setContainerTag($containerTag)
    {
        $this->containerTag = $containerTag;

        return $this;
    }

    /**
     * Returns the container tag ID option.
     *
     * @access  public
     * @return  string
     */
    public function getContainerTagId()
    {
        return $this->containerTagId;
    }

    /**
     * Sets  the container tag ID option.
     *
     * @access  public
     * @return  $this
     */
    public function setContainerTagId($containerTagId)
    {
        $this->containerTagId = $containerTagId;

        return $this;
    }

    /**
     * Returns the item tag option.
     *
     * @access  public
     * @return  string
     */
    public function getItemTag()
    {
        return $this->itemTag;
    }

    /**
     * Sets the item tag option.
     *
     * @access  public
     * @return  $this
     */
    public function setItemTag($itemTag)
    {
        $this->itemTag = $itemTag;

        return $this;
    }

    /**
     * Returns the HTML for the delimiter.
     *
     * @access  public
     * @return  string
     */
    protected function delimiter()
    {
        $str = ' <span';
        if (! empty($this->delimiterClass)) {
            $str .= ' class="'.$this->delimiterClass.'"';
        }
        $str .= '>';
        $str .= $this->delimiter;
        $str .= '</span> ';

        return $str;
    }
}