<?php

namespace Snap\Menu\Renderer;

class Breadcrumb extends BaseBreadcrumbRenderer implements MenuRendererInterface
{
    /**
     * The HTML to display between menu items
     *
     * @var string
     */
    protected $delimiter = ' &gt; ';

    /**
     * Display the current active breadcrumb item?
     *
     * @var boolean
     */
    protected $displayCurrent = true;

    /**
     * The root home link.
     *
     * @var string
     */
    protected $homeLink = 'Home';

    // --------------------------------------------------------------------

    /**
     * Renders the menu output in a breadcrumb format
     *
     * @access  public
     * @return  string
     */
    public function render()
    {
        $str = '';

        $activeItems = $this->builder->getActiveItems();

        $num = count($activeItems) - 1;
        if (! empty($this->homeLink)) {
            if (is_array($this->homeLink)) {
                $homeLink = each($this->homeLink);
                $homeItem = $this->builder->create($homeLink['key'], [
                    'label' => $homeLink['value'],
                    'link'  => $homeLink['key'],
                ]);
                $homeAnchor = $this->link($homeItem);
            } else {
                $homeItem = $this->builder->create('home', ['label' => $this->homeLink, 'link' => $this->homeLink]);
                $homeAnchor = $this->link($homeItem);
            }

            if (! empty($this->itemTag)) {
                $str .= $this->openItem($homeItem);
            }

            $str .= $homeAnchor;
            if ($num >= 0) {
                $str .= $this->delimiter();
            }
            if (! empty($this->itemTag)) {
                $str .= $this->closeItem();
            }
        }

        for ($i = $num; $i >= 0; $i--) {
            $id = $activeItems[$i];
            $item = $this->builder->find('id', $id);

            if (! empty($item)) {
                $label = $this->label($item);

                if (! empty($this->itemTag)) {
                    $str .= $this->openItem($item);
                }
                if ($i != 0) {
                    $str .= $this->link($item);
                    $str .= $this->delimiter();
                } else {
                    if ($this->displayCurrent) {
                        $str .= $label;
                    }
                }
                if (! empty($this->itemTag)) {
                    $str .= $this->closeItem();
                }
            }
        }

        $return = '';
        if (! empty($str)) {
            $return .= $this->openContainer();
            $return .= $str;
            $return .= $this->closeContainer();
        }

        return $return;
    }
}