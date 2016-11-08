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

    /**
     * Constructs a new data object
     * 
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        if (isset($this->data[$offset])) {
            unset($this->data[$offset]);
        }
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

}
