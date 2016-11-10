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
        return $this->getPropertyValue($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->setPropertyValue($offset, $value);
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
        return $this->getPropertyValue($name);
    }

    public function __set($name, $value)
    {
        $this->setPropertyValue($name, $value);
    }

    public function __isset($name)
    {
        return $this->isPropertyValueSet($name);
    }

    public function __unset($name)
    {
        $this->unsetPropertyValue($name);
    }

    private function getPropertyValue($name)
    {
        if (isset($this->properties[$name], $this->properties[$name][0])) {
            return call_user_func($this->properties[$name][0]);
        }
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    private function setPropertyValue($name, $value)
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

    public function toArray()
    {
        $result = [];
        foreach ($this->properties as $name => $temp) {
            $value = $this->getPropertyValue($name);
            if ($value instanceof \IvoPetkov\DataObject || $value instanceof \IvoPetkov\DataCollection) {
                $result[$name] = $value->toArray();
            } else {
                $result[$name] = $value;
            }
        }
        foreach ($this->data as $name => $value) {
            if (array_key_exists($name, $result) === false) {
                if ($value instanceof \IvoPetkov\DataObject || $value instanceof \IvoPetkov\DataCollection) {
                    $result[$name] = $value->toArray();
                } else {
                    $result[$name] = $value;
                }
            }
        }
        ksort($result);
        return $result;
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

}
