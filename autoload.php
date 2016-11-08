<?php

/*
 * Data Collection
 * https://github.com/ivopetkov/data-collection
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

$classes = array(
    'IvoPetkov\DataCollection' => 'src/DataCollection.php',
    'IvoPetkov\DataObject' => 'src/DataObject.php'
);

spl_autoload_register(function ($class) use ($classes) {
    if (isset($classes[$class])) {
        require __DIR__ . '/' . $classes[$class];
    }
}, true);
