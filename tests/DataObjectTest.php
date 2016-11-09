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
class DataObjectTest extends DataCollectionTestCase
{

    /**
     *
     */
    public function testConstructor()
    {
        $data = [
            'property1' => 'a',
            'property2' => 'b',
            'property3' => 'c'
        ];
        $object = new DataObject($data);
        $this->assertTrue($object->property1 === 'a');
        $this->assertTrue($object->property2 === 'b');
        $this->assertTrue($object->property3 === 'c');
        $this->assertTrue($object->property4 === null);
    }

    /**
     *
     */
    public function testProperties()
    {
        $object = new DataObject();
        $this->assertTrue($object->property1 === null);
        $this->assertTrue($object->property2 === null);
        $this->assertTrue($object->property3 === null);
        $this->assertFalse(isset($object->property1));
        $this->assertFalse(isset($object->property2));
        $this->assertFalse(isset($object->property3));
        $object->property1 = 'a';
        $object->property2 = 'b';
        $this->assertTrue($object->property1 === 'a');
        $this->assertTrue($object->property2 === 'b');
        $this->assertTrue($object->property3 === null);
        $this->assertTrue(isset($object->property1));
        $this->assertTrue(isset($object->property2));
        $this->assertFalse(isset($object->property3));
        unset($object->property2);
        $this->assertTrue($object->property1 === 'a');
        $this->assertTrue($object->property2 === null);
        $this->assertTrue($object->property3 === null);
        $this->assertTrue(isset($object->property1));
        $this->assertFalse(isset($object->property2));
        $this->assertFalse(isset($object->property3));
        $object->property1 = 'aa';
        $object->property1 = 'aaa';
        $object->property2 = 'b';
        $this->assertTrue($object->property1 === 'aaa');
        $this->assertTrue($object->property2 === 'b');
        $this->assertTrue($object->property3 === null);
        $this->assertTrue(isset($object->property1));
        $this->assertTrue(isset($object->property2));
        $this->assertFalse(isset($object->property3));
    }

    /**
     *
     */
    public function testArrayAccess()
    {
        $object = new DataObject();
        $this->assertTrue($object['property1'] === null);
        $this->assertTrue($object['property2'] === null);
        $this->assertTrue($object['property3'] === null);
        $this->assertFalse(isset($object['property1']));
        $this->assertFalse(isset($object['property2']));
        $this->assertFalse(isset($object['property3']));
        $object['property1'] = 'a';
        $object['property2'] = 'b';
        $this->assertTrue($object['property1'] === 'a');
        $this->assertTrue($object['property2'] === 'b');
        $this->assertTrue($object['property3'] === null);
        $this->assertTrue(isset($object['property1']));
        $this->assertTrue(isset($object['property2']));
        $this->assertFalse(isset($object['property3']));
        unset($object['property2']);
        $this->assertTrue($object['property1'] === 'a');
        $this->assertTrue($object['property2'] === null);
        $this->assertTrue($object['property3'] === null);
        $this->assertTrue(isset($object['property1']));
        $this->assertFalse(isset($object['property2']));
        $this->assertFalse(isset($object['property3']));
        $object['property1'] = 'aa';
        $object['property1'] = 'aaa';
        $object['property2'] = 'b';
        $this->assertTrue($object['property1'] === 'aaa');
        $this->assertTrue($object['property2'] === 'b');
        $this->assertTrue($object['property3'] === null);
        $this->assertTrue(isset($object['property1']));
        $this->assertTrue(isset($object['property2']));
        $this->assertFalse(isset($object['property3']));
    }

    /**
     *
     */
    public function testDefinProperty()
    {
        $object = new DataObject();
        $object->defineProperty('property1', [
            'get' => function($context) {
                if ($context->rawData->property1 === null) {
                    return 'unknown';
                } else {
                    return $context->rawData->property1;
                }
            },
            'set' => function($value) {
                return $value + 1;
            }
        ]);
        $this->assertTrue($object->property1 === 'unknown');
        $object->property1 = 10;
        $this->assertTrue($object->property1 === 11);
    }

    /**
     *
     */
    public function testDynamicPropertyContext()
    {
        $object = new DataObject();
        $object->defineProperty('property1', [
            'get' => function($context) {
                return $context->rawData->property2;
            },
            'set' => function($value, $context) {
                $context->object->property2 = $value;
            }
        ]);
        $object->property2 = 5;
        $this->assertTrue($object->property1 === 5);
        $object->property1 = 6;
        $this->assertTrue($object->property1 === 6);
        $this->assertTrue($object->property2 === 6);
    }

}
