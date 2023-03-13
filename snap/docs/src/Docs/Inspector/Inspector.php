<?php

namespace Snap\Docs\Inspector;

class Inspector
{
    use BaseReflectionTrait;

    public $name;

    protected $parent;
    protected $class;
    protected $traits;
    protected $methods;
    protected $props;
    protected $constants;
    protected $interfaces;
    protected $reflection;

    public function __construct($class)
    {
        $this->reflection = new \ReflectionClass($class);
        $this->name = $this->reflection->getName();
    }

    public function baseName()
    {
        return class_basename($this->reflection->getName());
    }

    public function parent()
    {
        if ($this->reflection->getParentClass()) {
            return new Inspector($this->reflection->getParentClass()->getName());
        }

        return null;
    }

    public function type()
    {
        if ($this->reflection->isTrait()) {
            return trans('docs::autodoc.trait');
        } elseif ($this->reflection->isInterface()) {
            return trans('docs::autodoc.interface');
        } else {
            return trans('docs::autodoc.class');
        }
    }

    public function methods($types = ['public'])
    {
        if (!isset($this->methods)) {
            $methods = [];

            foreach ($this->reflection->getMethods() as $method) {
                $methods[$method->getName()] = new MethodInspector($method);
            }

            $this->methods = new MethodsCollection($methods);
        }

        return $this->methods->filter(function($method) use ($types) {
            if ((empty($types) && $method->isPublic()) || (!empty($types) && in_array('public', $types))) {
                return true;
            } elseif ($method->isProtected() && (in_array('protected', $types) || in_array(\ReflectionMethod::IS_PROTECTED, $types))) {
                return true;
            } elseif ($method->isPrivate() && (in_array('private', $types) || in_array(\ReflectionMethod::IS_PRIVATE, $types))) {
                return true;
            }

            return false;
        });
    }

    public function method($name)
    {
        $methods = $this->methods();

        return $methods[$name] ?? null;
    }

    public function props($types = ['public', 'protected'])
    {
        if (!isset($this->props)) {

            $props = [];

            foreach ($this->reflection->getProperties() as $prop) {
                $props[$prop->getName()] = new PropertyInspector($prop);
            }
            $this->props = new PropertiesCollection($props);
        }

        return $this->props->filter(function($prop) use ($types) {

            if ((empty($types) && $prop->isPublic()) || (!empty($types) && $prop->isPublic() && (in_array('public', $types) || in_array(\ReflectionProperty::IS_PUBLIC, $types)))) {
                return true;
            } elseif ($prop->isProtected() && (in_array('protected', $types) || in_array(\ReflectionProperty::IS_PROTECTED, $types))) {
                return true;
            } elseif ($prop->isPrivate() && (in_array('private', $types) || in_array(\ReflectionProperty::IS_PRIVATE, $types))) {
                return true;
            }

            return false;
        });
    }

    public function prop($name)
    {
        $props = $this->props();

        return $props[$name] ?? null;
    }

    public function constants()
    {
        if (!isset($this->constants)) {

            $constants = [];

            foreach ($this->reflection->getConstants() as $key => $value) {
                $constants[$key] = new ConstantInspector($key, $value);
            }
            $this->constants = new ConstantsCollection($constants);
        }

        return $this->constants;
    }

    public function constant($name)
    {
        $constants = $this->constants();

        return $constants[$name] ?? null;
    }

    public function traits()
    {
        if (is_null($this->traits)) {
            $traits = [];

            foreach ($this->reflection->getTraits() as $trait) {
                $traits[$trait->getName()] = new TraitInspector($trait);
            }

            $this->traits = new TraitsCollection($traits);
        }

        return $this->traits;
    }

    public function trait($name)
    {
        $traits = $this->traits();

        return $traits[$name] ?? null;
    }

    public function interfaces()
    {
        if (is_null($this->interfaces)) {
            $interfaces = [];

            foreach ($this->reflection->getInterfaces() as $interface) {
                $interfaces[$interface->getName()] = new InterfaceInspector($interface);
            }

            $this->interfaces = new InterfacesCollection($interfaces);
        }

        return $this->interfaces;
    }

    public function interface($name)
    {
        $interfaces = $this->interfaces();

        return $interfaces[$name] ?? null;
    }

    public function canAutoDoc()
    {
        return config('snap.docs.restrict_auto_docs') || !empty($this->comment()->tag('autodoc'));
    }

    public static function isInspectable($class)
    {
        $classParts = explode('::', $class);
        $class = $classParts[0];
        return class_exists($class) || trait_exists($class) || interface_exists($class);
    }

}