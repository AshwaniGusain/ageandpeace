<?php

namespace Snap\Menu;

class Item implements ItemInterface
{
    /**
     * The id of the menu item.
     * Important for doing parent/child relationship lookups.
     * Often times it's the same as the link.
     *
     * @var string
     */
    protected $id = '';

    /**
     * The text displayed.
     *
     * @var string
     */
    protected $label = '';

    /**
     * The link.
     *
     * @var string
     */
    protected $link = '';

    /**
     * ButtonLink attributes.
     *
     * @var string
     */
    protected $linkAttributes = '';

    /**
     * Item element attributes (e.g. li attributes).
     *
     * @var string
     */
    protected $itemAttributes = '';

    /**
     * The ID of it's parent element.
     *
     * @var string
     */
    protected $parent = null;

    /**
     * Determines what active conditions need to be met
     * in order for it to have an active state.
     *
     * @var string
     */
    protected $active = '';

    /**
     * An array of tags (strings) you want to associate with the menu ite.
     * Important for filtering.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Create a new item.
     *
     * @param  string   The id of the menu item
     * @param  array    An array of properties to set
     * @return void
     */
    public function __construct($id, array $props = [])
    {
        $this->setId($id);

        // set the link to the same as the ID for default
        $this->setLink($id);

        // grab the last segment and set the label
        $tmp = explode('/', $id);
        $label = ucfirst(end($tmp));
        $this->setLabel($label);
        if (isset($props['auto_parent'])) {
            $this->setAutoParent($props['auto_parent']);
        }

        if (! empty($props) AND is_array($props)) {
            $this->assign($props);
        }
    }

    /**
     * Sets object properties
     *
     * @access public
     * @param  array    An array of properties to set
     * @return \Snap\Menu\Item
     */
    public function assign($prop, $value = null)
    {
        // If an array, then we will loop through it and assign values
        if (is_array($prop)) {
            foreach ($prop as $key => $val) {
                $key = $this->normalizePropertyKey($key);
                $method = 'set'.ucfirst($key);
                // check first if a method exists
                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->throwError($key);
                }
            }
        } else {
            $method = 'set'.ucfirst($prop);

            // check first if a method exists
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                $this->throwError($prop);
            }
        }

        return $this;
    }

    /**
     * Sets the id property.
     *
     * @access public
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id property.
     *
     * @access public
     * @param  string
     * @return Snap\Menu\Item
     */
    public function setId($id)
    {
        if (is_numeric($id)) {
            $id = (int) $id;
        }
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the label property.
     *
     * @access public
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets label property.
     *
     * @access public
     * @param  string
     * @return Snap\Menu\Item
     */
    public function setLabel($label)
    {
        //$this->label = $str = $this->encodeString($label);
        $this->label = $str = $label;

        return $this;
    }

    /**
     * Returns the link property.
     *
     * @access public
     * @return string
     */
    public function getLink()
    {
        if (! isset($this->link) AND is_string($this->getId())) {
            return $this->getId();
        }

        return $this->link;
    }

    /**
     * Sets the link property.
     *
     * @access public
     * @param  string
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        // normalize code should go here
        return $this;
    }

    /**
     * Returns the link attribute property.
     *
     * @access public
     * @return string
     */
    public function getLinkAttributes()
    {
        return $this->linkAttributes;
    }

    /**
     * Sets the link attribute property.
     *
     * @access public
     * @param  mixed    An array or string of properties to set
     * @return $this
     */
    public function setLinkAttributes($attributes)
    {
        $this->linkAttributes = $this->attrs($attributes);

        return $this;
    }

    /**
     * Returns the item attribute property.
     *
     * @access public
     * @return string
     */
    public function getItemAttributes()
    {
        return $this->itemAttributes;
    }

    /**
     * Sets the item attribute property.
     *
     * @access public
     * @param  mixed    An array or string of properties to set
     * @return $this
     */
    public function setItemAttributes($attributes)
    {
        $this->itemAttributes = $this->attrs($attributes);

        return $this;
    }

    /**
     * Returns the parent property.
     *
     * @access public
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Sets parent property.
     *
     * @access public
     * @param  string
     * @return $this
     */
    public function setParent($parent)
    {
        if (is_numeric($parent)) {
            $parent = (int) $parent;
        }

        if ($parent === $this->getId()) {
            throw new \InvalidArgumentException("You cannot have a parent value equal to an id value or it may cause an infinite loop.");
        }
        $this->parent = $parent;

        return $this;
    }

    /**
     * Returns the tags property.
     *
     * @access public
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the tag property, object properties.
     *
     * @access public
     * @param  mixed
     * @return $this
     */
    public function setTags($tags)
    {
        if (is_string($tags)) {
            $tags = preg_split('#\s*,\s*#', $tags);
        }
        $this->tags = $tags;

        return $this;
    }

    /**
     * Returns the active property.
     *
     * @access public
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets active property.
     * A regular expression value that determines which menu items should be set active.
     * Special aliases of :children ('about$|about/.+'),
     * :any (.+) and :num ([0-9]+) can be used
     * (e.g. about/:children would highlight if the active item specified is about/history, about/contact, etc)
     *
     * @access public
     * @param  string
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Returns whether the item is active
     *
     * @access public
     * @return boolean
     */
    public function isActive()
    {
        return $this->active == true;
    }

    /**
     * Returns the object properties as an array
     *
     * @access  public
     * @return  array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Magic method for getting properties
     *
     * @access  protected
     * @param   string
     * @return  mixed
     */
    function __get($prop)
    {
        $method = 'get'.ucfirst($prop);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method], $prop);
        }
        $this->throwError($prop);
    }

    /**
     * Magic method for setting properties
     *
     * @access  protected
     * @param   string
     * @return  mixed
     */
    function __set($prop, $val)
    {
        $method = 'set'.ucfirst($prop);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method], $prop, $val);
        }
        $this->throwError($prop);
    }

    /**
     * Automatically set the parent if specified.
     *
     * @access public
     * @param  string
     * @return void
     */
    protected function setAutoParent($val)
    {
        if ($val === true && is_null($this->parent) && strpos($this->id, '/')) {
            $segs = explode('/', $this->id);
            array_pop($segs);
            $parent = implode('/', $segs);
            $this->setParent($parent);
        }
    }

    /**
     * Normalizes the property keys.
     *
     * @access public
     * @param  string
     * @return string
     */
    protected function normalizePropertyKey($key)
    {
        // camelize
        $key = lcfirst(str_replace(' ', '', ucwords(preg_replace('/[\s_]+/', ' ', $key))));
        if ($key == 'parent_id') {
            $key = 'parent';
        }

        return $key;
    }

    /**
     * Helper method to return a string for HTML attributes.
     *
     * @access  protected
     * @param   mixed   Takes a string or an array of HTML element attributes (e.g. array('title' => 'My Title' OR 'title="My Title')).
     * @return  string
     */
    protected function attrs($attrs)
    {
        $str = '';
        if (is_array($attrs)) {
            foreach ($attrs as $key => $val) {
                if (! empty($val) && ! is_numeric($key)) {
                    $str .= ' '.$key.'="'.$this->encodeString($val).'"';
                }
            }
        } elseif (is_string($attrs) && ! empty($attrs)) {
            $str .= ' '.$this->encodeString($attrs, false);
        }

        return $str;
    }

    /**
     * Helper method to encode a strings HTML entities.
     *
     * @access  protected
     * @param   int     The number of tabs to create
     * @return  string
     */
    protected function encodeString($str, $encodeQuotes = true)
    {
        $quotes = ($encodeQuotes) ? ENT_QUOTES : ENT_NOQUOTES;

        return htmlentities($str, $quotes, 'UTF-8', false);
    }

    /**
     * Helper mento to encode a strings HTML entities.
     *
     * @access  protected
     * @param   int     The number of tabs to create
     * @return  string
     */
    protected function throwError($prop)
    {
        throw new \InvalidArgumentException("Menu property {$prop} does not exist.");
    }
}

/* End of file Menu.php */
/* Location: ./modules/fuel/libraries/Menu.php */