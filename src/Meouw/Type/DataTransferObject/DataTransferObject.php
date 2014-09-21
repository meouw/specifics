<?php

namespace Meouw\Type\DataTransferObject;

/**
 * Class DataTransferObject
 */
abstract class DataTransferObject implements \ArrayAccess
{
    /** @var array a cache of property=>type specs for all DataTransform */
    private static $propertySpec;
    /** @var array */
    private $properties = array();

    /**
     * @param array $properties
     */
    final public function __construct(array $properties)
    {
        $propertySpec = $this->getPropertySpec();
        $missingKeys = array_diff_key($propertySpec, $properties);
        if ($missingKeys) {
            throw new \InvalidArgumentException('Not all expected keys present: '.implode(', ', array_keys($missingKeys)));
        }

        // check the types
        foreach ($propertySpec as $key => $types) {
            $typesArray = explode('|', $types);
            foreach ($typesArray as $type) {
                if ($this->validateType($type, $properties[$key])) continue 2;
            }
            throw new \InvalidArgumentException("Value for $key is not of type $types");
        }
        
        $this->properties = array_intersect_key($properties, $propertySpec);
    }

    /**
     * @return array
     */
    private function getPropertySpec()
    {
        $class = get_called_class();
        if (!isset(self::$propertySpec[$class])) {
            // read the docblock to find out what data this DataTransferObject must contain
            $r        = new \ReflectionClass($class);
            $docblock = $r->getDocComment();
            preg_match_all('/@property\s+([|\\\\\w]+)\s+\$?(\w+)/', $docblock, $props);

            self::$propertySpec[$class] = array_combine($props[2], $props[1]);
        }
        return self::$propertySpec[$class];
    }

    /**
     * @param string $type
     * @param mixed  $value
     *
     * @return bool
     */
    private function validateType($type, $value)
    {
        $lcType = strtolower($type);
        switch($lcType) {
            case 'null':
                return is_null($value);
            case 'mixed':
                return !is_null($value);
            case 'integer':
            case 'int':
                return is_numeric($value) && (int)$value == $value;
            case 'float':
                return is_numeric($value) && (float)$value == $value;
            case 'string':
                return is_string($value);
            case 'boolean':
            case 'bool':
                return is_bool($value);
            case 'array':
                return is_array($value);
            case 'object':
                return is_object($value);
            default:
                return $value instanceof $type;
        }
    }

    /**
     * @param $name
     * @throws \LogicException
     * @return mixed
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->getPropertySpec())) {
            throw new \LogicException("Property `$name` does not exist or not specified");
        }
        return $this->properties[$name];
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @throws \LogicException
     * @return void
     */
    public function __set($name, $value)
    {
        throw new \LogicException('Data Transfer Objects are immutable');
    }

    /**
     * @param string $name
     * @throws \LogicException
     * @return void
     */
    public function __unset($name)
    {
        throw new \LogicException('Data Transfer Objects are immutable');
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->properties[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \LogicException
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('DataTransferObject Objects are immutable');
    }

    /**
     * @param mixed $offset
     * @throws \LogicException
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('DataTransferObject Objects are immutable');
    }

}