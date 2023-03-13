<?php

namespace Snap\Menu\Renderer;

use Snap\Menu\Renderer\BaseDelimitedRenderer;
use Snap\Menu\Renderer\MenuRendererInterface;

class Delimited extends BaseDelimitedRenderer implements MenuRendererInterface {

    /**
     * The HTML to display between menu items.
     *
     * @var string
     */
    protected $delimiter = ' | ';

    /**
     * Renders the menu output in a delimited format (e.g. About | Products | Contact)
     *
     * @access  public
     * @param   array menu item data
     * @return  string
     */
    public function render()
    {
        $links = array();

        // ignore the menu items
        $items = $this->builder->items();
        foreach($items as $item)
        {
            $s = $this->openItem($item);
            $s .= $this->link($item);
            $s .= $this->closeItem($item);
            $links[] = $s;
        }
        $str = implode($this->delimiter, $links);
        
        $return = '';
        if (!empty($str))
        {
            $return .= $this->openContainer();
            $return .= $str;
            $return .= $this->closeContainer();
        }
        
        return $return;
    }
}