<?php

namespace Snap\Menu\Renderer;

abstract class BaseNestedRenderer extends BaseHtmlRenderer implements NestedMenuRendererInterface
{
    /**
     * The nesting depth to render from.
     *
     * @var int
     */
    protected $depth;

    /**
     * Determines whether to cascade the selected "active" class down the nesting tree.
     *
     * @var boolean
     */
    protected $cascadeSelected = true;

    /**
     * The class to be associated with the first menu item option of a nesting level.
     *
     * @var string
     */
    protected $firstClass = 'first';

    /**
     * The class to be associated with the last menu item option of a nesting level.
     *
     * @var string
     */
    protected $lastClass = 'last';

    /**
     * The class to be associated with any nested container element.
     *
     * @var string
     */
    protected $childClass = 'child';

    /**
     * The parent id to render from.
     *
     * @var string
     */
    protected $parent = null;

    /**
     * Placeholder. Renders the menu output in a basic ul -> li nested format
     *
     * @access  public
     * @return  string
     */
    public function render($items, $level = 0)
    {
        return '';
    }

    /**
     * Returns the depth option.
     *
     * @access  public
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Sets the depth option.
     *
     * @access  public
     * @return \Snap\Menu\Renderer\BaseNestedRenderer
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Returns the cascade selected option.
     *
     * @access  public
     * @return  string
     */
    public function getCascadeSelected()
    {
        return $this->cascadeSelected;
    }

    /**
     * Sets the cascade selected option.
     *
     * @access  public
     * @return $this
     */
    public function setCascadeSelected($cascadeSelected)
    {
        $this->cascadeSelected = $cascadeSelected;

        return $this;
    }

    /**
     * Returns the first class option.
     *
     * @access  public
     * @return  string
     */
    public function getFirstClass()
    {
        return $this->firstClass;
    }

    /**
     * Sets the first class option.
     *
     * @access  public
     * @return $this
     */
    public function setFirstClass($firstClass)
    {
        $this->firstClass = $firstClass;

        return $this;
    }

    /**
     * Returns the last class option.
     *
     * @access  public
     * @return  string
     */
    public function getLastClass()
    {
        return $this->lastClass;
    }

    /**
     * Sets the last class option.
     *
     * @access  public
     * @return $this
     */
    public function setLastClass($lastClass)
    {
        $this->lastClass = $lastClass;

        return $this;
    }

    /**
     * Returns the child class option.
     *
     * @access  public
     * @return  string
     */
    public function getChildClass()
    {
        return $this->childClass;
    }

    /**
     * Sets the child class option.
     *
     * @access  public
     * @param  string
     * @return $this
     */
    public function setChildClass($childClass)
    {
        $this->childClass = $childClass;

        return $this;
    }

    /**
     * Returns the parent.
     *
     * @access  public
     * @return  string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Sets the parent.
     *
     * @access  public
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Renders the menu output in a basic ul -> li nested format
     *
     * @access  protected
     * @return  array
     */
    protected function getRootItems()
    {
        // grab the root items to start the rendering process
        $items = $this->builder->childItems($this->getParent());

        return $items;
    }
}