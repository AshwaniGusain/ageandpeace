<?php

namespace Snap\Menu\Renderer;

abstract class BaseBreadcrumbRenderer extends BaseDelimitedRenderer
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
    protected $homeLink = 'home';

    /**
     * Returns the display current option.
     *
     * @access  public
     * @return  boolean
     */
    public function getDisplayCurrent()
    {
        return $this->displayCurrent;
    }

    /**
     * Sets the display current option.
     *
     * @access  public
     * @return  $this
     */
    public function setDisplayCurrent($displayCurrent)
    {
        $this->displayCurrent = $displayCurrent;

        return $this;
    }

    /**
     * Returns the home link.
     *
     * @access  public
     * @return  string
     */
    public function getHomeLink()
    {
        return $this->homeLink;
    }

    /**
     * Sets the home link.
     *
     * @access  public
     * @return  $this
     */
    public function setHomeLink($homeLink)
    {
        $this->homeLink = $homeLink;

        return $this;
    }
}