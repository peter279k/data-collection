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
    public function testDefineProperty()
    {
        $object = new DataObject();
        $object->defineProperty('property1', [
            'get' => function() use ($object) {
                if ($object->property1raw === null) {
                    return 'unknown';
                } else {
                    return $object->property1raw;
                }
            },
            'set' => function($value) use ($object) {
                $object->property1raw = $value;
            }
        ]);
        $this->assertTrue($object->property1 === 'unknown');
        $object->property1 = 10;
        $this->assertTrue($object->property1 === 10);
    }

}
