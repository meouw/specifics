<?php

namespace Meouw\Type\DataTransferObject;

abstract class Collection implements \IteratorAggregate, \Countable
{
    private static $meta = array();
    private $members = array();

    private static function readAnnotation()
    {
        $class = get_called_class();
        if (!isset(self::$meta[$class]['type'])) {
            $r = new \ReflectionClass($class);
            $docComment = $r->getDocComment();
            preg_match('/@property\s+([\w\\\\]+)\[\]\s+\$(\w+)/', $docComment, $spec);
            self::$meta[$class] = array('type' => $spec[1], 'alias' => $spec[2]);
        }
        return self::$meta[$class];
    }

    public function __construct(array $members = array())
    {
        foreach ($members as $member) {
            $this->add($member);
        }
    }

    public function getType()
    {
        $meta = self::readAnnotation();
        return $meta['type'];
    }

    private function getAlias()
    {
        $meta = self::readAnnotation();
        return $meta['alias'];
    }

    public function add($dataTransferObject)
    {
        $type = $this->getType();
        if (is_array($dataTransferObject)) {
            $dataTransferObject = new $type($dataTransferObject);
        }
        if (!$dataTransferObject instanceof $type) {
            throw new \InvalidArgumentException("Object must be of type $type");
        }
        $this->members[] = $dataTransferObject;
    }

    public function __get($name)
    {
        if ($name === $this->getAlias()) {
            return $this->members;
        }

        throw new \LogicException('Unknown property ' . $name);
    }

    public function __set($name, $value)
    {
        throw new \LogicException('The members cannot be modified directly - use '.get_class($this).'::add');
    }

    public function getIterator()
    {
        return new \ArrayObject($this->members);
    }

    public function count()
    {
        return count($this->members);
    }
}