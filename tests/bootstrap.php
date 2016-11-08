<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

class DataCollectionTestCase extends PHPUnit_Framework_TestCase
{

    function setUp()
    {
        require __DIR__ . '/../vendor/autoload.php';
    }

}

class DataCollectionAutoloaderTestCase extends PHPUnit_Framework_TestCase
{

    function setUp()
    {
        require __DIR__ . '/../autoload.php';
    }

}
