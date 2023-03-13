<?php

namespace Snap\Menu;

use BadMethodCallException;
use InvalidArgumentException;
use Snap\Menu\Renderer\ArrayCollection;
use Snap\Menu\Renderer\Basic;
use Snap\Menu\Renderer\Breadcrumb;
use Snap\Menu\Renderer\Collapsible;
use Snap\Menu\Renderer\Delimited;
use Snap\Menu\Renderer\PageTitle;

class MenuBuilder
{
    /**
     * The items in the menu.
     *
     * @var array
     */
    protected $items = [];

    /**
     * The root id value... can be null or 0.
     *
     * @var mixed
     */
    protected $rootValue = null;

    /**
     * The active menu item's ID.
     *
     * @var string
     */
    protected $active = '';

    /**
     * The PHP class to use for the item.
     *
     * @var string
     */
    protected $itemClass = Item::class;

    /**
     * An array of menu items in the active path.
     *
     * @var array
     */
    protected $activeItems = [];

    /**
     * Determines whether to automatically create the parent value of the item based on the ID of the item.
     * Sorry to disappoint that it doesn't do more then that... been working on that solution for a while.
     *
     * @var boolean
     */
    protected $autoParent = false;

    /**
     * An array of PHP classes associated with a key and used for different rendering options.
     *
     * @var array
     */
    protected $renderers = [
        'array'       => ArrayCollection::class,
        'basic'       => Basic::class,
        'breadcrumb'  => Breadcrumb::class,
        'collapsible' => Collapsible::class,
        'delimited'   => Delimited::class,
        'title'       => PageTitle::class,
    ];

    /**
     * The render class for rendering the HTML output of the menu.
     *
     * @var array
     */
    protected $renderClass = Basic::class;

    /**
     * Renderer options.
     *
     * @var array
     */
    protected $renderOptions = [];

    /**
     * Constructor for creating menus.
     *
     * @access  public
     * @return  void
     */
    public function __construct()
    {
        // setup existing renderers
        foreach ($this->renderers as $key => $renderer) {
            $this->extend($key, $renderer);
        }
    }

    /**
     * Clears the array of items set.
     *
     * @access  public
     * @return  void
     */
    public function clear()
    {
        $this->items = [];
    }

    /**
     * Renders the menu output.
     *
     * @access  public
     * @param   string  The active menu item (optional).
     * @param   string  Basic, breadcrumb, title, collapsible, delimited or array (optional).
     * @param   mixed   Additional options for the type of menu (optional).
     * @return  string
     */
    public function render($active = null, $type = null, $options = [])
    {
        // set the parent menu item to start the rendering process
        if (isset($active)) {
            $this->setActive($active);
        }

        // look through all items to see if there is an active value and if so set the active state accordingly
        $this->setSelected();

        // set the items in the active path
        $this->setActiveItems($this->getActive());

        // set the renderer (if it set)
        $this->setRenderer($type);

        // grab the renderType class
        $renderer = new $this->renderClass($this);

        // for nested  
        //if (! isset($options['parent'])) {
        //   $options['parent'] = $this->rootValue;
        //}

        $options = $this->mergeOptions($options);

        if (! empty($options)) {
            $renderer->setOptions($options);
        }

        return $renderer->render();
    }

    /**
     * Sets the options for the renderer.
     *
     * @access  public
     * @param   mixed   Either a string key for the option or an array of renderer options.
     * @param   array   If a string key is specified for the option in the first parameter, this parameter will act as the value for that option (optional).
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setOptions($key, $val = null)
    {
        if (is_array($key)) {
            $this->renderOptions = $key;
        } else {
            $this->renderOptions[$key] = $val;
        }

        return $this;
    }

    /**
     * Merge the options for the renderer.
     *
     * @access  public
     * @param   array   An array of renderer options.
     * @return  array
     */
    public function mergeOptions($options)
    {
        $options = array_merge($this->renderOptions, (array) $options);

        return $options;
    }

    /**
     * Sets the menu renderer.
     *
     * @access  public
     * @param   string  Basic, breadcrumb, title, collapsible, delimited or array (optional).
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setRenderer($type)
    {
        if (! empty($type)) {
            // throw an error if the render type mapping doesn't exist
            if (! isset($this->renderers[$type])) {
                $className = get_class($this);
                throw new InvalidArgumentException("Invalid render type parameter recieved for {$className}::render()");
            }

            // grab the renderType class
            $this->renderClass = $this->renderers[$type];
        }

        return $this;
    }

    /**
     * Creates a menu item object.
     *
     * @access  public
     * @param   string  Menu item id.
     * @param   array   An array of Item property values. possible array keys are id, label, link, itemAttributes, linkAttributes, active, parent, and tags (optional).
     * @return  \Snap\Menu\Item
     */
    public function create($id, $props = [])
    {
        $label = null;

        $props = $this->normalizeItem($props);

        if (! isset($props['parent']) && strpos($id, '/') === false) {
            $props['parent'] = $this->rootValue;
        }

        // create menu item object
        $itemClass = $this->getItemClass();
        $item = new $itemClass($id, $props);

        return $item;
    }

    /**
     * Adds a menu item object to the collection.
     * Possible property keys are id, label, link, itemAttributes, linkAttributes, active, parent, and tags
     *
     * @access  public
     * @param   string  Menu item id.
     * @param   array   An array of Item property values. possible array keys are id, label, link, itemAttributes, linkAttributes, active, parent, and tags (optional)
     * @return  $this
     */
    public function add($id, $props = [])
    {
        // check to see if it's a nested array
        if (is_array($id)) {
            foreach ($id as $key => $item) {
                $this->add($key, $item);
            }
        } else {
            if ($id instanceof ItemInterface) {
                $this->items[$id->getId()] = $id;
            } elseif (is_array($props)) {
                $props['auto_parent'] = ($this->autoParent === true) ? true : false;
                $this->items[$id] = $this->create($id, $props);
            } else {
                $this->items[$id] = $props;
            }
        }

        return $this;
    }

    /**
     * Removes a menu item object from the collection.
     *
     * @access  public
     * @param   string  Menu item id.
     * @param   boolean Determines whether to delete all the children or not (optional).
     * @return  $this
     */
    public function remove($id, $cascade = true)
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        }

        // if cascade is set, it will remove all the children elements too
        if ($cascade === true) {
            foreach ($this->items as $item) {
                if ($item->getParent() == $id) {
                    $this->remove($item->getId());
                }
            }
        }

        return $this;
    }

    /**
     * Returns true/false depending on if the menu item key exists or not.
     *
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->items[$id]);
    }

    /**
     * Returns an array of menu items that will be used for rendering.
     *
     * @access  public
     * @param   mixed   Can be either a key/value array of items or an items property name to filter (e.g. $menu->items(array('id' => 'about')) optional)
     * @param   array   The value to filter by if the first parameter is a string specifying the item propert (e.g. $menu->items('id', 'about') optional)
     * @return  mixed   If both a key (1st parameter) and $val (2nd parameter) is passed, it will return just a single object
     */
    public function items($filters = [], $val = null)
    {
        if (is_null($filters)) {
            return $this->items;
        }
        $items = $this->find($filters, $val);

        return $items;
    }

    /**
     * Reduces the menu items based on the filtering provided.
     *
     * @access  public
     * @param   mixed   Can be either a key/value array of items or an items property name to filter (e.g. $menu->items(array('id' => 'about')))
     * @param   array   The value to filter by if the first parameter is a string specifying the item propert (e.g. $menu->items('id', 'about')optional)
     * @return  \Snap\Menu\MenuBuilder
     */
    public function filter($filters, $val = null)
    {
        // repeated here so that the results returned is an array
        if (is_string($filters)) {
            $filter = [$filters => $val];
        }
        $items = $this->find($filters);
        $this->items = $items;

        return $this;
    }

    /**
     * Searches the menu items based on the filtering and returns a single item if second parameter is not set and an array of items otherwise.
     *
     * @access  public
     * @param   mixed   Can be either a key/value array of items or an items property name to filter (e.g. $menu->items(array('id' => 'about')))
     * @param   array   The value to filter by if the first parameter is a string specifying the item propert (e.g. $menu->items('id', 'about') optional)
     * @return  mixed   If both a key (1st parameter) and $val (2nd parameter) is passed, it will return just a single object
     */
    public function find($filters, $val = null)
    {
        if (empty($filters)) {
            return $this->items;
        }

        $one = false;
        if (is_string($filters)) {
            $filters = [$filters => $val];
            $one = true;
        }

        $items = [];

        if (! empty($filters)) {
            foreach ($filters as $key => $filter) {
                $isNot = substr($key, -1, strlen($key)) == '!';
                $key = str_replace([' ', '!'], '', $key);

                foreach ($this->items as $id => $item) {
                    $method = 'get'.ucfirst($key);
                    $val = $item->$method();

                    $include = false;

                    if ($isNot === true) {
                        if ((is_string($val) && ! preg_match('#^'.$filter.'$#', $val))) {
                            $include = true;
                        } else {
                            if (is_array($val)) {
                                if (is_string($filter) && ! in_array($filter, $val)) {
                                    $include = true;
                                } else {
                                    if (is_array($filter)) {
                                        $include = true;
                                        foreach ($filter as $k => $f) {
                                            if (in_array($f, $val)) {
                                                $include = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ((is_string($val) && preg_match('#^'.$filter.'$#', $val))) {
                            $include = true;
                        } else {
                            if (is_array($val)) {
                                if (is_string($filter) && in_array($filter, $val)) {
                                    $include = true;
                                } else {
                                    if (is_array($filter)) {
                                        $include = false;
                                        foreach ($filter as $k => $f) {
                                            if (in_array($f, $val)) {
                                                $include = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($include === true) {
                        $items[$id] = $item;
                    }
                }
            }
        }

        // if the first parameter is a string, 
        // then we will assume they just want 1 returned
        if (! empty($one)) {
            return current($items);
        }

        return $items;
    }

    /**
     * Loads an external file or an array to create menu items.
     *
     * @access  public
     * @param   mixed   Either an array or a path to a file to load (string)
     * @param   boolean Determines whether the file should append or replace the items (optional)
     * @param   boolean Determines whether the file being loaded is JSON and should be decoded (optional)
     * @param   boolean Determines whether to fail silently if the file doesn't exist (optional)
     * @return  \Snap\Menu\MenuBuilder
     */
    public function load($file, $append = false, $isJSON = false, $silentFail = false)
    {
        if (is_string($file)) {
            $arr = $this->loadFile($file, $isJSON, $silentFail);
        } elseif (is_iterable($file)) {
            $arr = $file;
        } else {
            $className = get_class($this);
            throw new InvalidArgumentException("Invalid variable specified for {$className}::load()");
        }

        // if append is false, then we will simply ovwerwrite the values
        if ($append === false) {
            $this->clear();
        }

        $this->add($arr);

        return $this;
    }

    /**
     * Loads an external file or an array from a nested array hierarchy to create menu items.
     *
     * @access  public
     * @param   mixed   Either an array or a path to a file to load (string)
     * @param   boolean Determines whether the file should append or replace the items (optional)
     * @param   boolean Determines whether the file being loaded is JSON and should be decoded (optional)
     * @param   boolean Determines whether to fail silently if the file doesn't exist (optional)
     * @return  \Snap\Menu\MenuBuilder
     */
    public function loadHierarchy($file, $append = false, $isJSON = false, $silentFail = false)
    {
        $arr = [];
        if (is_string($file)) {
            $arr = $this->loadFile($file, $isJSON, $silentFail);
        } elseif (is_iterable($file)) {
            $arr = $file;
        }

        $parent = null;
        foreach ($arr as $key => $props) {
            $this->addFromHierarchy($key, $props);
        }

        return $this;
    }


    /**
     * Helper method to recursively add to the menu object any nested children.
     *
     * @access  protected
     * @param   string
     * @param   array
     * @param   string
     * @return  void
     */
    protected function addFromHierarchy($id, $props, $parent = null)
    {
        $props = $this->normalizeItem($props);

        if (isset($props['children'])) {
            $children = $props['children'];

            unset($props['children']);
            $this->add($id, $props);

            foreach ($children as $key => $child) {
                $this->addFromHierarchy($key, $child, $id);
            }
        } else {
            $props['parent'] = $parent;
            $this->add($id, $props);
        }
    }

    /**
     * Loads a file that should contain menu item data.
     *
     * @access  protected
     * @param   string
     * @param   bool
     * @param   bool
     * @return  array
     */
    protected function loadFile($file, $isJSON, $silentFail)
    {
        if (! file_exists($file)) {
            if ($silentFail) {
                return $this;
            }
            $className = get_class($this);
            throw new InvalidArgumentException("Invalid load path for {$className}::load()");
        }

        // decode JSON if JSON is specified
        if ($isJSON) {
            ob_start();
            include($file);
            $contents = ob_get_clean();

            $arr = json_decode($contents, true);
        } else {
            // include the file
            $arr = include($file);
        }

        if (empty($arr) || ! is_iterable($arr)) {
            $className = get_class($this);
            throw new InvalidArgumentException("Invalid variable specified for {$className}::load()");
        }

        return $arr;
    }

    /**
     * Returns the active menu item ID.
     *
     * @access  public
     * @return  string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets the active menu item ID.
     *
     * @access  public
     * @param   string
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setActive($active)
    {
        $this->active = trim($active, '/ ');

        return $this;
    }

    /**
     * Sets which item should be selected based on any item specific active values
     *
     * @access  public
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setSelected()
    {
        // if (!$this->getActive())
        // {
        //  $this->setActive(Request::path());
        // }

        $active = $this->getActive();

        $return = [];

        foreach ($this->items as $id => $item) {
            $selected = [];

            // Capture all that have selected states so we can loop through later
            if ($item->getActive()) {
                $selected[$id] = $item->getActive();
            }

            // Now loop through the selected states
            foreach ($selected as $sId => $activeRegex) {
                $return[$sId] = $item;
                $link = $item->getLink();
                $match = str_replace(':children', $link.'$|'.$link.'/.+', $activeRegex);
                $match = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $match));

                // Does the RegEx match?
                if (preg_match('#^'.$match.'$#', $active, $matches)) {
                    $this->setActive($sId);
                }
            }
        }

        return $this;
    }

    /**
     * Adds a renderer class.
     *
     * @access  public
     * @param   string The key to associate with the rederer (e.g. 'basic', 'breadcrumb').
     * @param   string The class to associate with the type.
     * @return  \Snap\Menu\MenuBuilder
     */
    public function extend($type, $class)
    {
        $this->renderers[$type] = $class;

        return $this;
    }

    /**
     * Returns the root value.
     *
     * @access  public
     * @return  mixed Either null or 0.
     */
    public function getRootValue()
    {
        return $this->rootValue;
    }

    /**
     * Sets what the root value of the menu item should be.
     *
     * @access  public
     * @param   mixed
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setRootValue($rootValue)
    {
        $this->rootValue = $rootValue;

        return $this;
    }

    /**
     * Returns an array of menu items in the active path.
     *
     * @access  public
     * @return  array
     */
    public function getActiveItems()
    {
        return $this->activeItems;
    }

    /**
     * Gets the items in the active menu path
     *
     * @access  public
     * @param   string Active element
     * @param   boolean First time iterating through (optional)?
     * @return  string
     */
    protected function setActiveItems($active, $firstTime = true)
    {
        static $activeItems;

        // reset it if is called more then once
        if ($firstTime) {
            $activeItems = [];
        }
        if (isset($this->items[$active])) {
            $activeParent = $this->items[$active]->getParent();

            // to normalize so we can do a strict comparison
            if (ctype_digit($activeParent)) {
                $activeParent = (int) $activeParent;
            }
        } else {
            return null;
        }

        if (! in_array($active, $activeItems)) {
            $activeItems[] = $active;
        }

        foreach ($this->items as $key => $val) {
            // to normalize so we can do a strict comparison
            if (ctype_digit($key)) {
                $key = (int) $key;
            }

            if ($key === $activeParent && ! empty($key)) {
                //echo $key .' - '.$activeParent.'<br>';
                if (isset($this->items[$key])) {
                    $activeItems[] = $key;
                }
                $this->setActiveItems($key, false);
            }
        }
        $this->activeItems = $activeItems;

        return $this;
    }

    /**
     * Gets the menu items based on the parent.
     *
     * @access  public
     * @param   mixed Parent id can be either a string or an integer value (optional).
     * @return  array
     */
    public function childItems($parent = null)
    {
        if (empty($items)) {
            $items = $this->items;
        }

        $childItems = [];

        foreach ($items as $key => $item) {
            if (is_numeric($parent)) {
                $parent = (int) $parent;
            }

            if ($parent === $item->getParent()) {
                $childItems[$key] = $item;
            }
        }

        return $childItems;
    }

    /**
     * Sets the autoParent which determines whether to automatically create the items parent based on the ID.
     *
     * @access  public
     * @param   bool
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setAutoParent($auto)
    {
        $this->autoParent = $auto;

        return $this;
    }

    /**
     * Returns the item class.
     *
     * @access  public
     * @return  string
     */
    public function getItemClass()
    {
        return $this->itemClass;
    }

    /**
     * Sets the class to be used for a menu item. The default is \Snap\Menu\Item.
     *
     * @access  public
     * @param   string
     * @return  \Snap\Menu\MenuBuilder
     */
    public function setItemClass($class)
    {
        $this->itemClass = $class;

        return $this;
    }

    /**
     * Normalizes the properties of a menu item.
     *
     * @access  protected
     * @param   array
     * @return  array
     */
    protected function normalizeItem($props)
    {
        if (is_string($props)) {
            $props = ['label' => $props];
        } elseif (is_object($props)) {
            $props = get_object_vars($props);
        }

        return $props;
    }

    /**
     * Magic method
     *
     * @access  public
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }


    /**
     * Magic method that will call a renderer if the method name is prefixed with "render"  (e.g. renderBasic, renderTitle, renderBreadcrumb... etc).
     *
     * @access  public
     * @param   string  Method name
     * @param   array   An array of arguments passed.
     * @return  string
     */
    public function __call($method, $args)
    {
        if (preg_match("/^render(.*)/", $method, $found)) {
            $type = strtolower($found[1]);
            if (array_key_exists(strtolower($type), $this->renderers)) {

                $active = (isset($args[0])) ? $args[0] : null;
                $options = (isset($args[1])) ? $args[1] : null;

                return $this->render($active, $type, $options);
            }
        }

        $className = get_class($this);
        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }
}