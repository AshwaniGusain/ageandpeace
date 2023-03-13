<?php

namespace Snap\Menu\Renderer;

class PageTitle extends BaseBreadcrumbRenderer implements MenuRendererInterface
{
    /**
     * The direction the title should be rendered.
     * Options are "asc" and "desc"
     *
     * @var string
     */
    protected $order = 'asc';

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

        $homeLink = $this->homeLink;
        if (! empty($homeLink)) {
            if (is_array($homeLink)) {
                $homeLink = reset($homeLink);
            }
        }

        if ($this->order == 'desc') {
            for ($i = 0; $i <= $num; $i++) {
                $id = $activeItems[$i];
                $item = $this->builder->find('id', $id);
                $label = $this->label($item);

                if ($i != 0) {
                    $str .= $this->delimiter;
                }
                $str .= $label;
            }
            if (($num >= 0 && ! empty($homeLink)) || (empty($homeLink) && $num > 0)) {
                $str .= $this->delimiter;
            }
            $str .= $homeLink;
        } else {
            $str .= $homeLink;
            if (($num >= 0 && ! empty($homeLink)) || (empty($homeLink) && $num > 0)) {
                $str .= $this->delimiter;
            }
            for ($i = $num; $i >= 0; $i--) {
                $id = $activeItems[$i];
                $item = $this->builder->find('id', $id);
                $label = $this->label($item);
                $str .= $label;
                if ($i != 0) {
                    $str .= $this->delimiter;
                }
            }
        }

        // strip any tags
        $str = $this->encodeString($str);

        return $str;
    }

    /**
     * Returns the order.
     *
     * @access  public
     * @return  string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the order.
     *
     * @access  public
     * @return  $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}