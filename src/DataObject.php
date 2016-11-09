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
        return $this->isPropertyValueSet($offset);
    }

    public function offsetUnset($offset)
    {
        $this->unsetPropertyValue($offset);
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
        return $this->isPropertyValueSet($name);
    }

    public function __unset($name)
    {
        $this->unsetPropertyValue($name);
    }

    private function getProperty($name)
    {
        if (isset($this->properties[$name], $this->properties[$name][0])) {
            return call_user_func($this->properties[$name][0]);
        }
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    private function setProperty($name, $value)
    {
        if (isset($this->properties[$name], $this->properties[$name][1])) {
            $this->data[$name] = call_user_func($this->properties[$name][1], $value);
            return;
        }
        $this->data[$name] = $value;
    }

    private function isPropertyValueSet($name)
    {
        return isset($this->data[$name]) || isset($this->properties[$name]);
    }

    private function unsetPropertyValue($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    public function defineProperty($name, $options = [])
    {
        $this->properties[$name] = [
            isset($options['get']) ? $options['get'] : null,
            isset($options['set']) ? $options['set'] : null
        ];
    }

}
