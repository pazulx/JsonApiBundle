<?php

namespace Pazulx\JsonApiBundle\DTO;

use JMS\Serializer\Annotation\HandlerCallback;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Annotation\AccessType;

abstract class AbstractDTO
{
    private $changed = [];
    private $fields = [];
    private $initialized = false;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        if ($this->initialized) {
            throw new \Exception('Object already initialized!');
        }

        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC );

        foreach ($props as $prop) {
            $this->fields[] = $prop->getName();
            unset($this->{$prop->getName()});
        }

        $this->initialized = true;
    }

    public function getChanged()
    {
        return $this->changed;
    }

    public function __set($name, $value)
    {
        $this->changed[] = $name;

        return $this->$name = $value;
    }

    public function __get($name)
    {
        //property_exists($this, $name) return true even if property was unset
        if (array_key_exists($name, get_object_vars($this))) {
            return $this->$name;
        } elseif (in_array($name, $this->fields)) {
            return null;
        }

        trigger_error(sprintf('Undefined property: %s::$%s', get_class($this), $name), E_USER_NOTICE);

        return null;
    }

    public function bindTo($object)
    {
        if (!$this->initialized) {
            throw new \Exception('Object not initialized!');
        }

        foreach ($this->getChanged() as $field) {
            $method = 'set' . ucfirst($field);
            if (property_exists($this, $field) && method_exists($object, $method)) {
                $object->$method($this->$field);
            }
        }
    }

    public function getProperties()
    {
        return $this->fields;
    }
}
