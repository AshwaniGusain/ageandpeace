<?php

namespace Snap\Menu\Renderer;

class ArrayCollection extends BaseRenderer implements NestedMenuRendererInterface
{
    /**
     * The parent id to render from.
     *
     * @var string
     */
    protected $parent = null;

    /**
     * The nesting depth to render from.
     *
     * @var int
     */
    protected $depth;

    /**
     * Renders the menu output in a breadcrumb format.
     *
     * @access  public
     * @param   array   Menu item data
     * @param   int     The nesting level
     * @return  array
     */
    public function render($items = [], $level = -1)
    {
        if ($level == -1) {
            // grab the root items to start the rendering process
            $items = $this->builder->childItems($this->getParent());
        }

        $return = [];
        $depth = $this->depth;
        if (! empty($items) AND (isset($depth) AND $level < $depth) OR ! isset($depth)) {
            foreach ($items as $id => $item) {
                $subitems = $this->builder->childItems($item->getId());
                if ($item->getId() == $this->builder->getActive()) {
                    $item->setActive(true);
                }
                $new_key = $item->getId();
                $return[$item->getId()] = (method_exists($item, 'toArray')) ? $item->toArray() : get_object_vars($item);
                if (! empty($subitems)) {
                    $level = $level + 1;
                    $return[$new_key]['children'] = $this->render($subitems, $level);
                }
            }
        }

        return $return;
    }

    /**
     * Returns the depth option.
     *
     * @access  protected
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Sets the depth option.
     *
     * @access  protected
     * @return \Snap\Menu\Renderer\ArrayCollection
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

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
        if (is_null($this->parent)) {
            $this->parent = $this->builder->getRootValue();
        }

        return $this->parent;
    }

    /**
     * Sets the parent.
     *
     * @access  public
     * @return  $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }
}