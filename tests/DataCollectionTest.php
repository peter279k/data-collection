<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

use IvoPetkov\DataCollection;
use IvoPetkov\DataObject;

/**
 * @runTestsInSeparateProcesses
 */
class DataCollectionTest extends DataCollectionTestCase
{

    /**
     *
     */
    public function testConstructor()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'c');
        $this->assertTrue($collection->length === 3);
        foreach ($collection as $i => $object) {
            $this->assertTrue($object->value === $data[$i]['value']);
        }
    }

    /**
     *
     */
    public function testUpdate()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'c');
        $collection[2] = new DataObject(['value' => 'cc']);
        $this->assertTrue($collection[2]->value === 'cc');
        $collection[3] = new DataObject(['value' => 'dd']);
        $this->assertTrue($collection[3]->value === 'dd');
        $collection[4] = new DataObject(['value' => 'ee']);
        $this->assertTrue($collection[4]->value === 'ee');
        $this->assertTrue(isset($collection[4]));
        $collection[] = new DataObject(['value' => 'ff']);
        $this->assertTrue($collection[5]->value === 'ff');

        $this->assertFalse(isset($collection[6]));

        $this->setExpectedException('\Exception');
        $collection[7] = new DataObject(['value' => 'gg']);
    }

    /**
     *
     */
    public function testOffsetSetWithInvalidOffsetType()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->offsetSet('invalid_offset', 'value');
    }

    /**
     *
     */
    public function testOffsetSetWithNullValue()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->offsetSet(2, 'invalid_value');
    }

    /**
     *
     */
    public function testOffsetExistsWithInvalidType()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->offsetExists('invalid_offset');
    }

    /**
     *
     */
    public function testOffsetUnsetWithInvalidType()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->offsetUnset('invalid_offset');
    }

    /**
     *
     */
    public function testOffsetGetWithInvalidType()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->offsetGet('invalid_offset');
    }

    /**
     *
     */
    public function testUnset()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'c');
        unset($collection[1]);
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'c');
    }

    /**
     *
     */
    public function testSetWithInvalidProperty()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->name = 'name';
    }

    /**
     *
     */
    public function testSetWithLengthIsReadonlyProperty()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->length = 5;
    }

    /**
     *
     */
    public function testGetWithInvalidProperty()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $property = $collection->invalid_property;
    }

    /**
     *
     */
    public function testIssetWithInvalidProperty()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $invalidIsset = isset($collection->invalid_property);
    }

    /**
     *
     */
    public function testUnsetWithInvalidProperty()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        unset($collection->invalid_property);
    }

    /**
     *
     */
    public function testUnsetWithReadonlyProperty()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        unset($collection->length);
    }

    /**
     *
     */
    public function testFilter()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->filter(function($object) {
            return $object->value !== 'b';
        });
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'c');
    }

    /**
     *
     */
    public function testFilterWithInvalidCallback()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->filter('invalid_callback');
    }

    /**
     *
     */
    public function testFilterBy()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->filterBy('value', 'c');
        $this->assertTrue($collection[0]->value === 'c');
    }

    /**
     *
     */
    public function testFilterByWithInvalidPropertyType()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->filterBy(5, 'c');
    }

    /**
     *
     */
    public function testSort()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->sort(function($object1, $object2) {
            return strcmp($object1->value, $object2->value);
        });
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'c');

        $collection->sort(function($object1, $object2) {
            return strcmp($object1->value, $object2->value) * -1;
        });
        $this->assertTrue($collection[0]->value === 'c');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'a');
    }

    /**
     *
     */
    public function testSortWithInvalidCallback()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->sort('invalid_callback');
    }

    /**
     *
     */
    public function testSortBy()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->sortBy('value');
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'c');
        $collection->sortBy('value', 'desc');
        $this->assertTrue($collection[0]->value === 'c');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'a');
    }

    /**
     *
     */
    public function testSortByWithInvalidPropertyType()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->sortBy(5);
    }

    /**
     *
     */
    public function testSortByWithInvalidOrder()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->sortBy('value', 'invalid_order');
    }

    /**
     *
     */
    public function testLength()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertTrue(isset($collection->length));
        $collection->pop();
        $this->assertTrue($collection->length === 2);
    }

    /**
     *
     */
    public function testShiftAndUnshift()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertTrue($collection->length === 3);
        $object = $collection->shift();
        $this->assertTrue($object->value === 'a');
        $this->assertTrue($collection->length === 2);
        $collection->unshift(['value' => 'a']);
        $this->assertTrue($collection[0]->value === 'a');
        $this->assertTrue($collection->length === 3);
    }

    /**
     *
     */
    public function testUnshiftWithNullArgument()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->unshift(null);
    }

    /**
     *
     */
    public function testPopAndPush()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertTrue($collection->length === 3);
        $object = $collection->pop();
        $this->assertTrue($object->value === 'c');
        $this->assertTrue($collection->length === 2);
        $collection->push(['value' => 'c']);
        $this->assertTrue($collection[2]->value === 'c');
        $this->assertTrue($collection->length === 3);
    }

    /**
     *
     */
    public function testPushWithNullArgument()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->push(null);
    }

    /**
     *
     */
    public function testReverse()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->reverse();
        $this->assertTrue($collection[0]->value === 'c');
        $this->assertTrue($collection[1]->value === 'b');
        $this->assertTrue($collection[2]->value === 'a');

        $collection->push(['value' => 'd']);
        $collection->reverse();
        $this->assertTrue($collection[0]->value === 'd');
        $this->assertTrue($collection[1]->value === 'a');
        $this->assertTrue($collection[2]->value === 'b');
        $this->assertTrue($collection[3]->value === 'c');
    }

    /**
     *
     */
    public function testMap()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->map(function($object) {
            $object->value .= $object->value;
            return $object;
        });
        $this->assertTrue($collection[0]->value === 'aa');
        $this->assertTrue($collection[1]->value === 'bb');
        $this->assertTrue($collection[2]->value === 'cc');
    }

    /**
     *
     */
    public function testMapWithInvalidCallback()
    {
        $this->setExpectedException('Exception');
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $collection->map('invalid_callback');
    }

    /**
     *
     */
    public function testSlice()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $this->assertInstanceOf('\IvoPetkov\DataCollection', $collection->slice(0, 1));
    }

    /**
     *
     */
    public function testToArray()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $array = $collection->toArray();
        $this->assertTrue($array === $data);
    }

    /**
     *
     */
    public function testToJSON()
    {
        $data = [
            ['value' => 'a'],
            ['value' => 'b'],
            ['value' => 'c']
        ];
        $collection = new DataCollection($data);
        $json = $collection->toJSON();
        $expectedResult = '[{"value":"a"},{"value":"b"},{"value":"c"}]';
        $this->assertTrue($json === $expectedResult);
    }

    /**
     *
     */
    public function testInstanceDataWithNull()
    {
        $this->setExpectedException('Exception');
        $collection = new DataCollection([null]);
    }

}
