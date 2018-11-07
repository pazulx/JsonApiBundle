<?php

namespace Pazulx\JsonApiBundle\DTO;

abstract class AbstractDTO
{
    private $changed = [];

    public function __construct()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC );

        foreach ($props as $prop) {
            unset($this->{$prop->getName()});
        }
    }


    private function getChanged()
    {
        return $this->changed;
    }

    public function __set($name, $value)
    {
        $this->changed[] = $name;

        return $this->$name = $value;
    }

    public function bind($object)
    {
        foreach ($this->getChanged() as $field) {
            $method = 'set' . ucfirst($field);
            if (property_exists($this, $field) && method_exists($object, $method)) {
                $object->$method($this->$field);
            }
        }
    }
}
