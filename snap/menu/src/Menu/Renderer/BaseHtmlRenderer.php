<?php

namespace Snap\Menu\Renderer;

use InvalidArgumentException;
use Snap\Menu\MenuBuilder;

abstract class BaseHtmlRenderer extends BaseRenderer
{
    /**
     * The item view template.
     * If left blank, it will render the link with the label inside.
     *
     * @var string
     */
    protected $itemTemplate = null;

    /**
     * The base URL for the links.
     * If left blank, it will look for a url function.
     * If that's not found, it will try and determine
     * the path from the $_SERVER['SCRIPT_NAME'].
     *
     * @var string
     */
    protected $baseUrl = null;

    /**
     * The container tag that holds the menu items.
     *
     * @var string
     */
    protected $containerTag = 'ul';

    /**
     * The container tag HTML id attribute value.
     *
     * @var string
     */
    protected $containerTagId = '';

    /**
     * CSS classes that can be added to the container tags.
     * Nested arrays can be used for nested structures
     * with each array item being associated with a single
     * tag. If a string value is provided, then it will apply
     * to all of the menu items at the given nesting level.
     * The key of "default" will apply to all items.
     *
     * @var array
     */
    protected $containerTagClasses = [];

    /**
     * The item tag
     *
     * @var string
     */
    protected $itemTag = 'li';

    /**
     * CSS classes that can be added to the item tags.
     * Nested arrays can be used for nested structures
     * with each array item being associated with a single
     * tag. If a string value is provided, then it will apply
     * to all of the menu items at the given nesting level.
     * The key of "default" will apply to all items.
     *
     * @var array
     */
    protected $itemTagClasses = [];

    /**
     * CSS classes that can be added to the link tags.
     * Nested arrays can be used for nested structures
     * with each array item being associated with a single
     * tag. If a string value is provided, then it will apply
     * to all of the menu items at the given nesting level.
     * The key of "default" will apply to all items.
     *
     * @var array
     */
    protected $itemLinkClasses = [];

    /**
     * The item id prefix.
     *
     * @var string
     */
    protected $itemIdPrefix = '';

    /**
     * The active applied to the active menu item tag.
     *
     * @var string
     */
    protected $activeClass = 'active';

    /**
     * The class applied to menu items without a link.
     *
     * @var string
     */
    protected $nolinkClass = 'nolink';

    /**
     * A function to run for the label value of every menu item.
     *
     * @var \Closure
     */
    protected $preRenderFunc = null;

    /**
     * The number of tabs to indent the rendered HTML.
     *
     * @var int
     */
    protected $tabs = 0;

    /**
     * The item tag.
     *
     * @var string
     */
    protected $builder = null;

    /**
     * Create a new Renderer for the MenuBuilder.
     *
     * @param  \Snap\Menu\MenuBuilder $builder
     * @return void
     */
    public function __construct(MenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Creates an array of CSS classes to be applied to the container HTML element.
     *
     * @access  public
     * @param   int     The nested level (optional)
     * @return  array
     */
    public function containerClasses($level = 0)
    {
        $cssClasses = [];

        if ($level >= 1 && ! empty($this->childClass)) {
            $cssClasses[] = $this->childClass;
        }

        if (! empty($this->containerTagClasses)) {
            if (isset($this->containerTagClasses['default'])) {
                $cssClasses[] = $this->containerTagClasses['default'];
            }
            if (isset($this->containerTagClasses[$level])) {
                $cssClasses[] = $this->containerTagClasses[$level];
            }
        }

        return $cssClasses;
    }

    /**
     * Creates an array of CSS classes to be applied to the container HTML element.
     *
     * @access  public
     * @param   array   Menu item data
     * @param   int     Current level (optional)
     * @param   int     Current item index (optional)
     * @param   boolean Whether the item is the last in the list (optional)
     * @return  array
     */
    public function itemClasses($item, $level = 0, $i = 0, $last = false)
    {
        $cssClasses = [];
        $active = $item->getId();

        if (! empty($this->firstClass) && $i == 0) {
            $cssClasses[] = $this->firstClass;
        }
        if (! empty($this->lastClass) && $last) {
            $cssClasses[] = $this->lastClass;
        }

        $activeItems = $this->builder->getActiveItems();
        $activeClass = $this->activeClass;

        if ($this->builder->getActive() == $active || (! empty($this->cascadeSelected) && is_array($activeItems) && in_array($active, $activeItems)) && $activeClass) {
            if (! empty($activeClass)) {
                $cssClasses[] = $this->activeClass;
            }
        }

        if (! empty($this->itemTagClasses)) {
            $optCssClasses = $this->itemTagClasses;
        }
        if (! empty($this->itemTagClasses['default'])) {
            $cssClasses[] = $this->itemTagClasses['default'];
        }

        if (isset($optCssClasses[$level])) {
            if (is_array($optCssClasses[$level]) && ! empty($optCssClasses[$level][$i])) {
                $cssClasses[] = $optCssClasses[$level][$i];
            } else {
                if (is_string($optCssClasses[$level])) {
                    $cssClasses[] = $optCssClasses[$level];
                }
            }
        }

        return $cssClasses;
    }

    /**
     * Generates the HTML output for the CSS classes.
     *
     * @access  public
     * @param   mixed   An array or comman separate string of class names
     * @return  string
     */
    public function renderCssClasses($classes)
    {
        if (is_string($classes)) {
            $classes = preg_split('#\s*,\s*#', $classes);
        }
        $str = '';
        if (! empty($classes)) {
            $str = ' class="';
            $str .= implode(' ', $classes);
            $str .= '"';
        }

        return $str;
    }

    /**
     * Returns the text to be used for the menu item.
     *
     * @access  public
     * @param   \Snap\Menu\Item
     * @return  string
     */
    public function label($item)
    {
        $str = $item->getLabel();

        // if it is an array, we will assume they want the 'label' key
        if ($this->preRenderFunc) {
            $str = call_user_func($this->preRenderFunc, $str, $item);
        }

        return $str;
    }

    /**
     * Creates a link element.
     *
     * @access  public
     * @param   \Snap\Menu\Item
     * @param   int
     * @return  string
     */
    public function link($item, $level = 0)
    {
        $str = '';
        $label = $this->label($item);

        $attrs = '';
        $link = $item->getLink();

        if (! empty($link)) {
            if (method_exists($item, 'getLinkAttributes') && $item->getLinkAttributes()) {
                $attrs = $item->getLinkAttributes();
            }

            $cssClasses = [];

            if (! empty($this->itemLinkClasses)) {
                if (isset($this->itemLinkClasses['default'])) {
                    $cssClasses[] = $this->containerTagClasses['default'];
                }
                if (isset($this->itemLinkClasses[$level])) {
                    $cssClasses[] = $this->itemLinkClasses[$level];
                }
            }

            if ($this->builder->getActive() == $item->getId() && $this->activeClass) {
                $cssClasses[] = $this->activeClass;
            }

            // extract out any classes from the attrs and append to the cssClasses array
            $attrClasses = $this->extractClasses($attrs);
            $cssClasses = array_merge($cssClasses, $attrClasses);

            unset($attrClasses);

            if (! empty($cssClasses)) {
                $attrs .= $this->renderCssClasses($cssClasses);
            }

            if (! preg_match('#^https?://#i', $link)) {
                $link = $this->getBaseUrl().$link;
            }

            $str .= '<a href="'.$link.'"';
            if (! empty($attrs)) {
                $str .= $attrs;
            }

            $str .= '>'.$label.'</a>';
        } else {
            $str .= '<span class="'.$this->nolinkClass;
            if (! empty($active) && $this->getActive() == $active && $this->activeClass) {
                $str .= ' '.$this->activeClass;
            }
            $str .= '">';
            $str .= $label;
            $str .= '</span>';
        }

        return $str;
    }

    /**
     * Helper method to returns a number of tabs.
     *
     * @access  public
     * @param   int     The number of tabs to create
     * @return  string
     */
    public function tab($tabs = 0)
    {
        $tabs = $tabs + $this->tabs;
        if ($tabs < 0) {
            return '';
        }

        return str_repeat("\t", $tabs);
    }

    /**
     * Create the ID for a item element.
     *
     * @access  public
     * @param   mixed takes a string or an array
     * @return  string
     */
    public function createId($item)
    {
        if (empty($this->itemIdPrefix)) {
            return;
        }
        $id = strtolower(str_replace('/', '_', $item->getId()));
        if (empty($id)) {
            $id = 'home';
        }

        return $this->itemIdPrefix.$id;
    }

    /**
     * Sets render option properties (any method with "set" prefix).
     *
     * @access  public
     * @param  array  An array with the key being the protected properties name and the value being the value to set
     * @return  $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $method => $value) {
            $methodName = 'set'.ucfirst($method);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            } else {
                $className = get_class($this);
                throw new InvalidArgumentException("Invalid option {$method} passed to {$className}::setOptions()");
            }
        }

        return $this;
    }

    /**
     * Returns the item template for the menu item.
     *
     * @access  public
     * @return  $this
     */
    public function getItemTemplate()
    {
        return $this->itemTemplate;
    }

    /**
     * Sets the item template for the.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setItemTemplate($template)
    {
        $this->itemTemplate = $template;

        return $this;
    }

    /**
     * Returns the base URL for links.
     *
     * @access  public
     * @return  string
     */
    public function getBaseUrl()
    {
        if (is_null($this->baseUrl)) {
            if (function_exists('url')) {
                return url('').'/';
            } else {
                $baseUrl = $_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

                if (! empty($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) !== 'off' OR $_SERVER['SERVER_PORT'] == 443) {
                    return "https://".$baseUrl;
                } else {
                    return "http://".$baseUrl;
                }
            }
        }

        return trim($this->baseUrl, '/').'/';
    }

    /**
     * Sets the base URL for links.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;

        return $this;
    }

    /**
     * Returns the container tag.
     *
     * @access  public
     * @return  string
     */
    public function getContainerTag()
    {
        return $this->containerTag;
    }

    /**
     * Sets the container tag.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setContainerTag($containerTag)
    {
        $this->containerTag = $containerTag;

        return $this;
    }

    /**
     * Returns the container tag HTML ID.
     *
     * @access  protected
     * @return  string
     */
    public function getContainerTagId()
    {
        return $this->containerTagId;
    }

    /**
     * Sets the container tag HTML ID.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setContainerTagId($containerTagId)
    {
        $this->containerTagId = $containerTagId;

        return $this;
    }

    /**
     * Returns the container CSS Classes.
     *
     * @access  public
     * @return  array
     */
    public function getContainerTagClasses()
    {
        return $this->containerTagClasses;
    }

    /**
     * Sets the container CSS Classes.
     *
     * @access  public
     * @param  array
     * @return  $this
     */
    public function setContainerTagClasses($containerTagClasses)
    {
        if (is_string($containerTagClasses)) {
            $containerTagClasses = ['default' => $containerTagClasses];
        }

        $this->containerTagClasses = $containerTagClasses;

        return $this;
    }

    /**
     * Returns the item tag.
     *
     * @access  public
     * @return  string
     */
    public function getItemTag()
    {
        return $this->itemTag;
    }

    /**
     * Sets the item tag.
     *
     * @access  public
     * @param   string
     * @return  $this
     */
    public function setItemTag($itemTag)
    {
        $this->itemTag = $itemTag;

        return $this;
    }

    /**
     * Returns the item tag classes.
     *
     * @access  public
     * @return  array
     */
    public function getItemTagClasses()
    {
        return $this->itemTagClasses;
    }

    /**
     * Sets the item tag classes.
     *
     * @access  public
     * @param   array
     * @return  $this
     */
    public function setItemTagClasses($itemTagClasses)
    {
        if (is_string($itemTagClasses)) {
            $itemTagClasses = ['default' => $itemTagClasses];
        }

        $this->itemTagClasses = $itemTagClasses;

        return $this;
    }

    /**
     * Returns the item link classes.
     *
     * @access  protected
     * @return  array
     */
    public function getItemLinkClasses()
    {
        return $this->itemLinkClasses;
    }

    /**
     * Sets the item link classes.
     *
     * @access  public
     * @param   array
     * @return  $this
     */
    public function setItemLinkClasses($itemLinkClasses)
    {
        if (is_string($itemLinkClasses)) {
            $itemLinkClasses = ['default' => $itemLinkClasses];
        }

        $this->itemLinkClasses = $itemLinkClasses;

        return $this;
    }

    /**
     * Returns the item ID prefix.
     *
     * @access  public
     * @return  string
     */
    public function getItemIdPrefix()
    {
        return $this->itemIdPrefix;
    }

    /**
     * Sets the item ID prefix.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setItemIdPrefix($itemIdPrefix)
    {
        $this->itemIdPrefix = $itemIdPrefix;

        return $this;
    }

    /**
     * Returns the active class to be used to indicate the active item.
     *
     * @access  public
     * @return  string
     */
    public function getActiveClass()
    {
        return $this->activeClass;
    }

    /**
     * Sets the active class to be used to indicate the active item.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setActiveClass($activeClass)
    {
        $this->activeClass = $activeClass;

        return $this;
    }

    /**
     * Returns the rendering function that can be used on item labels.
     *
     * @access  public
     * @return  mixed
     */
    public function getPreRenderFunc()
    {
        return $this->preRenderFunc;
    }

    /**
     * Sets the rendering function that can be used on item labels.
     *
     * @access  public
     * @param  string
     * @return  $this
     */
    public function setPreRenderFunc($preRenderFunc)
    {
        $this->preRenderFunc = $preRenderFunc;

        return $this;
    }

    /**
     * Returns the number of tabs to indent the rendred HTML.
     *
     * @access  public
     * @return  string
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * Sets the number of tabs to indent the rendered HTML.
     *
     * @access  public
     * @param  int
     * @return  $this
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;

        return $this;
    }

    /**
     * Creates opening HTML container that holds the menu items.
     *
     * @access  protected
     * @param   int     The nested level (optional)
     * @return  string
     */
    protected function openContainer($level = 0)
    {
        if (! $this->containerTag) {
            return '';
        }

        $str = '';
        $str .= "<".$this->containerTag;
        if ($this->containerTagId && $level == 0) {
            $str .= " id=\"".$this->containerTagId."\"";
        }
        $cssClasses = $this->containerClasses($level);

        if (! empty($cssClasses)) {
            $str .= $this->renderCssClasses($cssClasses);
        }

        if ($this->containerTag) {
            $str .= ">\n";
        }

        return $str;
    }

    /**
     * Creates closing HTML container that holds the menu items.
     *
     * @access  protected
     * @return  string
     */
    protected function closeContainer()
    {
        if (! $this->containerTag) {
            return '';
        }

        $str = '';
        if ($this->containerTag) {
            $str .= "</".$this->containerTag.">";
        }

        return $str;
    }

    /**
     * Creates the opening HTML output for the menu item.
     *
     * @access  protected
     * @param   array   Menu item data
     * @param   int     Current nesting level (optional)
     * @param   int     Current item index (optional)
     * @param   boolean Whether the item is the last in the list (optional)
     * @return  string
     */
    protected function openItem($item, $level = 0, $i = 0, $last = false)
    {
        if (! $this->itemTag) {
            return '';
        }

        $str = '';

        $attrs = (method_exists($item, 'getItemAttributes')) ? $item->getItemAttributes() : [];
        $str .= "<".$this->itemTag;

        // set id
        if ($this->itemIdPrefix && strpos($attrs, 'id=') === false) {
            $str .= ' id="'.$this->createId($item).'"';
        }

        // set styles
        $cssClasses = $this->itemClasses($item, $level, $i, $last);

        // extract out any classes from the attrs and append to the cssClasses array
        $attrClasses = $this->extractClasses($attrs);
        $cssClasses = array_merge($cssClasses, $attrClasses);
        unset($attrClasses);

        $str .= $attrs;

        if (! empty($cssClasses)) {
            $str .= $this->renderCssClasses($cssClasses);
        }
        $str .= '>';

        return $str;
    }

    /**
     * Creates the closing HTML output for the menu item.
     *
     * @access  protected
     * @return  string
     */
    protected function closeItem()
    {
        if (! $this->itemTag) {
            return '';
        }

        return "</".$this->itemTag.">\n";
    }

    /**
     * Extract out any CSS classes from a string of HTML attributes
     *
     * @access  protected
     * @param   string
     * @return  array
     */
    protected function extractClasses(&$attrs)
    {
        $cssClasses = [];

        // extract out any classes from the attrs and append to the cssClasses array
        preg_match('#.*class="(.+)".*#iU', $attrs, $matches);
        if (! empty($matches[1])) {
            $attrs = trim(preg_replace('#(.*)class=".+"(.*)#iU', '$1$2', $attrs));
            $cssClasses[] = $matches[1];
        }

        return $cssClasses;
    }

    /**
     * Generates the HTML output for a single menu item (usually the link and text).
     *
     * @access  protected
     * @param   \Snap\Menu\Item
     * @param   int
     * @return  string
     */
    protected function renderItem($item, $id, $level = 0)
    {
        $str = '';

        if ($this->itemTemplate) {
            if (is_callable($this->itemTemplate)) {
                $str = call_user_func($this->itemTemplate, $item, $id, $level, $this);
            } elseif (method_exists($this->itemTemplate, '__toString')) {
                $str = $this->itemTemplate->__toString();
            } elseif (view()->exists($this->itemTemplate)) {
                $str = view($this->itemTemplate, ['item'     => $item,
                                                  'id'       => $id,
                                                  'level'    => $level,
                                                  'renderer' => $this,
                ]);
            }
        } else {
            $str = $this->link($item, $level);
        }

        return $str;
    }

    /**
     * Helper method to encode a strings HTML entities.
     *
     * @access  protected
     * @param   int     The number of tabs to create
     * @param   boolean Determines whether to encode quotes or not.
     * @return  string
     */
    protected function encodeString($str, $encodeQuotes = true)
    {
        $quotes = ($encodeQuotes) ? ENT_QUOTES : ENT_NOQUOTES;

        return htmlentities($str, $quotes, 'UTF-8', false);
    }
}