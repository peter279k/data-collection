<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov;

/**
 * 
 */
class DataObject implements \ArrayAccess
{

    /**
     * The object data
     * 
     * @var array 
     */
    private $data = [];

    /**
     * The registered object properties
     * 
     * @var array 
     */
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

    /**
     * 
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getPropertyValue($offset);
    }

    /**
     * 
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->setPropertyValue($offset, $value);
        }
    }

    /**
     * 
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->isPropertyValueSet($offset);
    }

    /**
     * 
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->unsetPropertyValue($offset);
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getPropertyValue($name);
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->setPropertyValue($name, $value);
    }

    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->isPropertyValueSet($name);
    }

    /**
     * 
     * @param string $name
     */
    public function __unset($name)
    {
        $this->unsetPropertyValue($name);
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    private function getPropertyValue($name)
    {
        if (isset($this->properties[$name], $this->properties[$name][0])) {
            return call_user_func($this->properties[$name][0]);
        }
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    private function setPropertyValue($name, $value)
    {
        if (isset($this->properties[$name], $this->properties[$name][1])) {
            $this->data[$name] = call_user_func($this->properties[$name][1], $value);
            return;
        }
        $this->data[$name] = $value;
    }

    /**
     * 
     * @param string $name
     * @return boolean
     */
    private function isPropertyValueSet($name)
    {
        return isset($this->data[$name]) || isset($this->properties[$name]);
    }

    /**
     * 
     * @param string $name
     */
    private function unsetPropertyValue($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    /**
     * Defines a new property
     * 
     * @param string $name The property name
     * @param array $options The property options ['get'=>callable, 'set'=>callable]
     * @throws \Exception
     */
    public function defineProperty($name, $options = [])
    {
        if (!is_string($name)) {
            throw new \Exception('The name must be of type string');
        }
        if (!is_array($options)) {
            throw new \Exception('The options must be of type array');
        }
        if (isset($options['get']) && !is_callable($options['get'])) {
            throw new \Exception('The options get attribute must be of type callable');
        }
        if (isset($options['set']) && !is_callable($options['set'])) {
            throw new \Exception('The options set attribute must be of type callable');
        }
        $this->properties[$name] = [
            isset($options['get']) ? $options['get'] : null,
            isset($options['set']) ? $options['set'] : null
        ];
    }

    /**
     * Returns the object data converted as an array
     * 
     * @return array The object data converted as an array
     */
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

    /**
     * Returns the object data converted as JSON
     * 
     * @return string The object data converted as JSON
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }

}
