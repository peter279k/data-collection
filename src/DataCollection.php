<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov;

/**
 * @property-read int $length The number of objects in the collection
 */
class DataCollection implements \ArrayAccess, \Iterator
{

    /**
     * The collection data objects
     * 
     * @var array 
     */
    private $data = [];

    /**
     * The pointer when the collection is iterated with foreach 
     * 
     * @var int
     */
    private $pointer = 0;

    /**
     * The list of actions (sort, filter, etc.) that must be applied to the collection
     * 
     * @var array 
     */
    private $actions = [];

    /**
     * Constructs a new data collection
     * 
     * @param array $data An array containing DataObjects or arrays that will be converted into DataObjects
     * @throws \Exception
     */
    public function __construct($data = [])
    {
        foreach ($data as $object) {
            $object = $this->getDataObject($object);
            if ($object === null) {
                $this->data = [];
                throw new \Exception('The data argument is not valid. It must be of type \IvoPetkov\DataObject or array.');
            }
            $this->data[] = $object;
        }
    }

    /**
     * Converts the data argument into a DataObject if needed
     * 
     * @param \IvoPetkov\DataObject|array $object The data to be converted into a DataObject if needed
     * @return \IvoPetkov\DataObject|null Returns a DataObject or null if the argument is not valid
     */
    private function getDataObject($object)
    {
        if ($object instanceof DataObject) {
            return $object;
        } elseif (is_array($object)) {
            return new DataObject($object);
        }
        return null;
    }

    /**
     * 
     * @param int $offset
     * @param \IvoPetkov\DataObject|null $value
     * @return void
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        if (!is_int($offset) && $offset !== null) {
            throw new \Exception('The offset must be of type int or null');
        }
        $this->update();
        $object = $this->getDataObject($value);
        if ($object === null) {
            throw new \Exception('The data argument is not valid. It must be of type \IvoPetkov\DataObject or array.');
        }
        if (is_null($offset)) {
            $this->data[] = $object;
            return;
        }
        if (is_int($offset) && $offset >= 0 && (isset($this->data[$offset]) || $offset === sizeof($this->data))) {
            $this->data[$offset] = $object;
            return;
        }
        throw new \Exception('The offset is not valid.');
    }

    /**
     * 
     * @param int $offset
     * @return boolean
     * @throws \Exception
     */
    public function offsetExists($offset)
    {
        if (!is_int($offset)) {
            throw new \Exception('The offset must be of type int');
        }
        $this->update();
        return isset($this->data[$offset]);
    }

    /**
     * 
     * @param int $offset
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        if (!is_int($offset)) {
            throw new \Exception('The offset must be of type int');
        }
        $this->update();
        if (isset($this->data[$offset])) {
            unset($this->data[$offset]);
            $this->data = array_values($this->data);
        }
    }

    /**
     * 
     * @param int $offset
     * @return \IvoPetkov\DataObject|null
     * @throws \Exception
     */
    public function offsetGet($offset)
    {
        if (!is_int($offset)) {
            throw new \Exception('The offset must be of type int');
        }
        $this->update();
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * 
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * 
     * @return \IvoPetkov\DataObject|null
     */
    public function current()
    {
        $this->update();
        return isset($this->data[$this->pointer]) ? $this->data[$this->pointer] : null;
    }

    /**
     * 
     * @return int
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * 
     */
    public function next()
    {
        ++$this->pointer;
    }

    /**
     * 
     * @return boolean
     */
    public function valid()
    {
        $this->update();
        return isset($this->data[$this->pointer]);
    }

    /**
     * Applies the pending actions to the data collection
     */
    private function update()
    {
        if (isset($this->actions[0])) {
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
                } elseif ($action[0] === 'reverse') {
                    $this->data = array_reverse($this->data);
                } elseif ($action[0] === 'map') {
                    $this->data = array_map($action[1], $this->data);
                }
            }
            $this->actions = [];
        }
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if ($name === 'length') {
            throw new \Exception('The length property is readonly');
        }
        throw new \Exception('Invalid property (' . (string) $name . ')');
    }

    /**
     * 
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if ($name === 'length') {
            $this->update();
            return sizeof($this->data);
        }
        throw new \Exception('Invalid property (' . (string) $name . ')');
    }

    /**
     * 
     * @param string $name
     * @return boolean
     * @throws \Exception
     */
    public function __isset($name)
    {
        if ($name === 'length') {
            return true;
        }
        throw new \Exception('Invalid property (' . (string) $name . ')');
    }

    /**
     * 
     * @param string $name
     * @throws \Exception
     */
    public function __unset($name)
    {
        if ($name === 'length') {
            throw new \Exception('Cannot unset the length property');
        }
        throw new \Exception('Invalid property (' . (string) $name . ')');
    }

    /**
     * 
     * @return array
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * Filters the elements of the collection using a callback function
     * 
     * @param callback $callback The callback function to use
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws \Exception
     */
    public function filter($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('The callback argument is not callable');
        }
        $this->actions[] = ['filter', $callback];
        return $this;
    }

    /**
     * Filters the elements of the collection by specific property value
     * 
     * @param string $property The property name
     * @param mixed $value The value of the property
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws \Exception
     */
    public function filterBy($property, $value)
    {
        if (!is_string($property)) {
            throw new \Exception('The property argument must be of type string');
        }
        $this->actions[] = ['filterBy', $property, $value];
        return $this;
    }

    /**
     * Sorts the elements of the collection using a callback function 
     * 
     * @param callback $callback The callback function to use
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws \Exception
     */
    public function sort($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('The callback argument is not callable');
        }
        $this->actions[] = ['sort', $callback];
        return $this;
    }

    /**
     * Sorts the elements of the collection by specific property
     * 
     * @param string $property The property name
     * @param string $order The sort order
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws \Exception
     */
    public function sortBy($property, $order = 'asc')
    {
        if (!is_string($property)) {
            throw new \Exception('The property argument must be of type string');
        }
        if ($order !== 'asc' && $order !== 'desc') {
            throw new \Exception('The order argument must be of type string with a value of \'asc\' or \'desc\'');
        }
        $this->actions[] = ['sortBy', $property, $order];
        return $this;
    }

    /**
     * Reverses the order of the objects in the collection
     * 
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     */
    public function reverse()
    {
        $this->actions[] = ['reverse'];
        return $this;
    }

    /**
     * Applies the callback to the objects of the collection
     * 
     * @param callback $callback The callback function to use
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws \Exception
     */
    public function map($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('The callback argument is not callable');
        }
        $this->actions[] = ['map', $callback];
        return $this;
    }

    /**
     * Prepends an object to the beginning of the collection
     * 
     * @param \IvoPetkov\DataObject|array $object The data to be prepended
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws Exception
     */
    public function unshift($object)
    {
        $this->update();
        $object = $this->getDataObject($object);
        if ($object === null) {
            throw new \Exception('The data argument is not valid. It must be of type \IvoPetkov\DataObject or array.');
        }
        array_unshift($this->data, $object);
        return $this;
    }

    /**
     * Shift an object off the beginning of the collection
     * 
     * @return \IvoPetkov\DataObject|null Returns the shifted object or null if the collection is empty
     */
    public function shift()
    {
        $this->update();
        return array_shift($this->data);
    }

    /**
     * Pushes an object onto the end of the collection
     * 
     * @param \IvoPetkov\DataObject|array $object The data to be pushed
     * @return \IvoPetkov\DataCollection Returns a reference to the collection
     * @throws Exception
     */
    public function push($object)
    {
        $this->update();
        $object = $this->getDataObject($object);
        if ($object === null) {
            throw new \Exception('The data argument is not valid. It must be of type \IvoPetkov\DataObject or array.');
        }
        array_push($this->data, $object);
        return $this;
    }

    /**
     * Pops an object off the end of collection
     * 
     * @return \IvoPetkov\DataObject|null Returns the poped object or null if the collection is empty
     */
    public function pop()
    {
        $this->update();
        return array_pop($this->data);
    }

    /**
     * Returns the collection data converted as an array
     * 
     * @return array The collection data converted as an array
     */
    public function toArray()
    {
        $this->update();
        $result = [];
        foreach ($this->data as $object) {
            $result[] = $object->toArray();
        }
        return $result;
    }

    /**
     * Returns the collection data converted as JSON
     * 
     * @return string The collection data converted as JSON
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }

}
