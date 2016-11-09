<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov;

class DataObject implements \ArrayAccess
{

    /**
     * The object data
     * 
     * @var array 
     */
    private $data = [];
    private $properties = [];

    /**
     * Constructs a new data object
     * 
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function offsetGet($offset)
    {
        return $this->getProperty($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->setProperty($offset, $value);
        }
    }

    public function offsetExists($offset)
    {
        return $this->isPropertySet($offset);
    }

    public function offsetUnset($offset)
    {
        $this->unsetProperty($offset);
    }

    public function __get($name)
    {
        return $this->getProperty($name);
    }

    public function __set($name, $value)
    {
        $this->setProperty($name, $value);
    }

    public function __isset($name)
    {
        return $this->isPropertySet($name);
    }

    public function __unset($name)
    {
        $this->unsetProperty($name);
    }

    private function getDynamicPropertyContext()
    {
        $context = new DataObject();
        $context->object = $this;
        $context->rawData = new DataObject($this->data);
        return $context;
    }

    private function getProperty($name)
    {
        if (isset($this->properties[$name], $this->properties[$name][0])) {
            $context = $this->getDynamicPropertyContext();
            $context->propertyName = $name;
            return call_user_func($this->properties[$name][0], $context);
        }
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    private function setProperty($name, $value)
    {
        if (isset($this->properties[$name], $this->properties[$name][1])) {
            $context = $this->getDynamicPropertyContext();
            $context->propertyName = $name;
            $this->data[$name] = call_user_func($this->properties[$name][1], $value, $context);
            return;
        }
        $this->data[$name] = $value;
    }

    private function isPropertySet($name)
    {
        return isset($this->data[$name]);
    }

    private function unsetProperty($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    public function registerProperty($name, $getCallback, $setCallback)
    {
        $this->properties[$name] = [$getCallback, $setCallback];
    }

}
