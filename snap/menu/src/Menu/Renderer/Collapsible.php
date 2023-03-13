<?php

namespace Snap\Menu\Renderer;

class Collapsible extends BaseNestedRenderer
{
    /**
     * The depth in which to start the collapsing.
     *
     * @var int
     */
    protected $collapsibleDepth = 1;

    /**
     * Returns the collapsible depth.
     *
     * @access  protected
     * @return  string
     */
    public function getCollapsibleDepth()
    {
        return $this->collapsibleDepth;
    }

    /**
     * Sets the collapsible depth.
     *
     * @access  public
     * @param  int
     * @return  $this
     */
    public function setCollapsibleDepth($collapsibleDepth)
    {
        $this->collapsibleDepth = $collapsibleDepth;

        return $this;
    }

    /**
     * Renders the menu output in a collapsed format
     *
     * @access  public
     * @param   array menu item data
     * @param   int depth level
     * @return  string
     */
    public function render($items = [], $level = 0)
    {
        if ($level == 0) {
            $items = $this->getRootItems();
        }

        $str = '';

        // add proper tabs and returns for the opening container element
        $tabs = ($level < 1) ? $this->tab(0) : $this->tab($level * 2);
        if ($level != 0) {
            $str .= "\n";
        }
        $str .= $tabs;
        $str .= $this->openContainer($level);

        // find start index
        $activeIndex = 0;
        $activeItems = $this->builder->getActiveItems();

        if (! empty($activeItems)) {
            foreach ($activeItems as $index => $item) {

                if (! empty($items[$item])) {
                    $activeIndex = $index;
                    break;
                }
            }
        }

        $childItems = [];

        $i = 0;

        // loop through base menu items and start drill down
        foreach ($items as $id => $item) {

            $this->label($item);

            //if ($activeIndex > -1 && isset($activeItems[$activeIndex]) && $id == $activeItems[$activeIndex]) {
            if (($this->collapsibleDepth && $level < $this->collapsibleDepth) || ($activeIndex > -1 && isset($activeItems[$activeIndex]) && $id == $activeItems[$activeIndex])) {

                $childItems = $this->builder->childItems($item->getId());

                // add proper tabs for the opening item element
                $tabs = ($level <= 0) ? $this->tab(1) : $this->tab(($level * 2) + 1);
                $str .= $tabs;

                $str .= $this->openItem($item, $level, $i, ($i == (count($items) - 1)));
                $str .= $this->link($item, $level);

                if (! empty($childItems)) {
                    $str .= $this->render($childItems, $level + 1);
                }

                if (! empty($this->itemTag)) {
                    // add proper tabs for the closing item element
                    $tabs = (! empty($childItems)) ? $this->tab(($level * 2) - 1) : "";
                    if (! empty($childItems)) {
                        $str .= "\n";
                    }
                    $str .= $tabs;

                    $str .= $this->closeItem();
                }
            } else {
                // add proper tabs for the opening item element
                $tabs = (empty($childItems)) ? $this->tab(($level * 2) - $this->tabs) : $this->tab((($level - 1) * 2) - $this->tabs);
                $str .= $this->tab(1);
                $str .= $tabs;
                $str .= $this->openItem($item, $level, $i, ($i == (count($items) - 1)));
                $str .= $this->link($item, $level);

                if (! empty($this->itemTag)) {
                    $str .= $this->closeItem();
                }
            }
            $i++;
        }

        if ($this->containerTag) {
            $tabs = (! empty($childItems)) ? $this->tab(($level - 1) * 2) : $this->tab($level * 2);
            $str .= $tabs;
            $str .= $this->closeContainer();
        }

        return $str;
    }
}