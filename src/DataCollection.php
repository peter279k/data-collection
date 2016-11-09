<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov;

class DataCollection implements \ArrayAccess, \Iterator
{

    /**
     * The collection data
     * 
     * @var array 
     */
    private $data = [];
    private $pointer = 0;
    private $actions = [];
    private $actionsVersion = 0;
    private $dataVersion = 0;

    /**
     * Constructs a new data collection
     * 
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $object) {
            $object = $this->getDataObject($object);
            if ($object === null) {
                $this->data = [];
                throw new Exception('');
            }
            $this->data[] = $object;
        }
    }

    private function getDataObject($object)
    {
        if ($object instanceof DataObject) {
            return $object;
        } elseif (is_array($object)) {
            return new DataObject($object);
        }
        return null;
    }

    public function offsetSet($offset, $value)
    {
        $this->updateData();
        if (is_null($offset)) {
            $this->data[] = $value;
            return;
        }
        if (is_int($offset) && $offset >= 0 && (isset($this->data[$offset]) || $offset === sizeof($this->data))) {
            $this->data[$offset] = $value;
            return;
        }
        throw new \Exception('');
    }

    public function offsetExists($offset)
    {
        $this->updateData();
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        $this->updateData();
        if (isset($this->data[$offset])) {
            unset($this->data[$offset]);
            $this->data = array_values($this->data);
        }
    }

    public function offsetGet($offset)
    {
        $this->updateData();
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        $this->updateData();
        return $this->data[$this->pointer];
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        ++$this->pointer;
    }

    public function valid()
    {
        $this->updateData();
        return isset($this->data[$this->pointer]);
    }

    private function updateData()
    {
        if ($this->actionsVersion !== $this->dataVersion) {
            foreach ($this->actions as $action) {
                if ($action[0] === 'filter') {
                    $temp = [];
                    foreach ($this->data as $index => $object) {
                        if (call_user_func($action[1], $object) === true) {
                            $temp[] = $object;
                        }
                    }
                    $this->data = $temp;
                    unset($temp);
                } else if ($action[0] === 'filterBy') {
                    $temp = [];
                    foreach ($this->data as $object) {
                        if ($object[$action[1]] === $action[2]) {
                            $temp[] = $object;
                        }
                    }
                    $this->data = $temp;
                    unset($temp);
                } elseif ($action[0] === 'sort') {
                    usort($this->data, $action[1]);
                } elseif ($action[0] === 'sortBy') {
                    usort($this->data, function($object1, $object2) use ($action) {
                        return strcmp($object1[$action[1]], $object2[$action[1]]) * ($action[2] === 'asc' ? 1 : -1);
                    });
                }
            }
        }
    }

    public function __set($name, $value)
    {
        throw new \Exception('');
    }

    public function __get($name)
    {
        if ($name === 'length') {
            $this->updateData();
            return sizeof($this->data);
        }
        throw new \Exception('');
    }

    public function __isset($name)
    {
        if ($name === 'length') {
            return true;
        }
        throw new \Exception('');
    }

    public function __unset($name)
    {
        throw new \Exception('');
    }

    public function __debugInfo()
    {
        return [$this->data];
    }

    public function filter($callback)
    {
        $this->actions[] = ['filter', $callback];
        $this->actionsVersion++;
        return $this;
    }

    public function filterBy($property, $value)
    {
        $this->actions[] = ['filterBy', $property, $value];
        $this->actionsVersion++;
        return $this;
    }

    public function sort($callback)
    {
        $this->actions[] = ['sort', $callback];
        $this->actionsVersion++;
        return $this;
    }

    public function sortBy($property, $order = 'asc')
    {
        $this->actions[] = ['sortBy', $property, $order];
        $this->actionsVersion++;
        return $this;
    }

    public function unshift($object)
    {
        $object = $this->getDataObject($object);
        if ($object === null) {
            throw new Exception('');
        }
        array_unshift($this->data, $object);
    }

    public function shift()
    {
        return array_shift($this->data);
    }

    public function push($object)
    {
        $object = $this->getDataObject($object);
        if ($object === null) {
            throw new Exception('');
        }
        array_push($this->data, $object);
    }

    public function pop()
    {
        return array_pop($this->data);
    }

}
