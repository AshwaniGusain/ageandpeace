<?php

namespace Snap\Menu\Renderer;

class Basic extends BaseNestedRenderer
{
    /**
     * Renders the menu output in a basic ul -> li nested format
     *
     * @access  public
     * @param array $items
     * @param   int     The nesting level
     * @return  string
     */
    public function render($items = [], $level = 0)
    {
        if ($level == 0) {
            $items = $this->getRootItems();
        }

        $depth = $this->depth;

        $str = "";
        if (! empty($items) && ((! is_null($depth) && $level < $depth)) || is_null($depth)) {
            // add proper tabs and returns for the opening container element
            $tabs = ($level < 1) ? "" : $this->tab(($level * 2));
            $str .= "\n".$tabs;

            // add opening container tag
            $str .= $this->openContainer($level);

            //$activeIndex = (count($this->builder->getActiveItems()) -1) - $level;
            $level = $level + 1;
            $i = 0;

            $childItems = [];

            // create menu items
            foreach ($items as $id => $item) {
                // add proper tabs for the opening item element
                $tabs = ($level <= 1) ? $this->tab(1) : $this->tab(($level * 2) - 1);
                $str .= $tabs;

                // add opening item tag
                $str .= $this->openItem($item, $level - 1, $i, ($i == (count($items) - 1)));
                $str .= $this->renderItem($item, $id, $level - 1);

                // determine if there are any sub menu items
                $childItems = $this->builder->childItems($item->getId());
                if (! empty($childItems)) {
                    $str .= $this->render($childItems, $level);
                }

                if (! empty($this->itemTag)) {
                    // add proper tabs for the closing item element
                    $tabs = (! empty($childItems) && $level != $depth) ? $this->tab($level - ($this->tabs + 1)) : "";
                    $str .= $tabs;

                    // add closing item tag
                    $str .= $this->closeItem();
                }
                $i++;
            }

            // add proper tabs and returns for the closing container element
            $tabs = ($level < 0) ? $this->tab($level) : $this->tab(($level * 2) - 2);
            $str .= $tabs;

            // add closing container tag
            $tabs = (! empty($childItems)) ? $level : $level - 1;
            $str .= $this->closeContainer()."\n".$this->tab($tabs);

            if ($level == 1) {
                $str = $this->tab(0).trim($str);
            }
        }

        return $str;
    }
}