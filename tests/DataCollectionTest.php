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

}
