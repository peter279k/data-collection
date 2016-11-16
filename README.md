# Data Collection

A familiar and powerful data collection abstraction for PHP

[![Build Status](https://travis-ci.org/ivopetkov/data-collection.svg)](https://travis-ci.org/ivopetkov/data-collection)
[![Latest Stable Version](https://poser.pugx.org/ivopetkov/data-collection/v/stable)](https://packagist.org/packages/ivopetkov/data-collection)
[![codecov.io](https://codecov.io/github/ivopetkov/data-collection/coverage.svg?branch=master)](https://codecov.io/github/ivopetkov/data-collection?branch=master)
[![License](https://poser.pugx.org/ivopetkov/data-collection/license)](https://packagist.org/packages/ivopetkov/data-collection)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c9ad5d49897f4c209236225b7d0c1c1c)](https://www.codacy.com/app/ivo_2/data-collection)

## Install via Composer

```shell
composer require ivopetkov/data-collection
```


## Documentation

#### IvoPetkov\DataCollection
##### Methods

```php
public __construct ( [ array $data = [] ] )
```

Constructs a new data collection

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$data`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;An array containing DataObjects or arrays that will be converted into DataObjects

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No value is returned.

```php
public \IvoPetkov\DataCollection filter ( callback $callback )
```

Filters the elements of the collection using a callback function

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$callback`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The callback function to use

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataCollection filterBy ( string $property , mixed $value )
```

Filters the elements of the collection by specific property value

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$property`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The property name

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$value`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The value of the property

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataCollection sort ( callback $callback )
```

Sorts the elements of the collection using a callback function 

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$callback`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The callback function to use

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataCollection sortBy ( string $property [, string $order = 'asc' ] )
```

Sorts the elements of the collection by specific property

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$property`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The property name

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$order`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The sort order

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataCollection reverse ( void )
```

Reverses the order of the objects in the collection

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataCollection map ( callback $callback )
```

Applies the callback to the objects of the collection

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$callback`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The callback function to use

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataCollection unshift ( \IvoPetkov\DataObject|array $object )
```

Prepends an object to the beginning of the collection

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$object`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The data to be prepended

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataObject|null shift ( void )
```

Shift an object off the beginning of the collection

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns the shifted object or null if the collection is empty

```php
public \IvoPetkov\DataCollection push ( \IvoPetkov\DataObject|array $object )
```

Pushes an object onto the end of the collection

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$object`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The data to be pushed

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns a reference to the collection

```php
public \IvoPetkov\DataObject|null pop ( void )
```

Pops an object off the end of collection

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returns the poped object or null if the collection is empty

```php
public array toArray ( void )
```

Returns the collection data converted as an array

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The collection data converted as an array

```php
public string toJSON ( void )
```

Returns the collection data converted as JSON

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The collection data converted as JSON

#### IvoPetkov\DataObject
##### Methods

```php
public __construct ( [ array $data = [] ] )
```

Constructs a new data object

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$data`

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No value is returned.

```php
public void defineProperty ( string $name [, array $options = [] ] )
```

Defines a new property

_Parameters_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$name`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The property name

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`$options`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The property options ['get'=>callable, 'set'=>callable]

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No value is returned.

```php
public array toArray ( void )
```

Returns the object data converted as an array

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The object data converted as an array

```php
public string toJSON ( void )
```

Returns the object data converted as JSON

_Returns_

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The object data converted as JSON

## License
Data Collection is open-sourced software. It's free to use under the MIT license. See the [license file](https://github.com/ivopetkov/data-collection/blob/master/LICENSE) for more information.

## Author
This library is created by Ivo Petkov. Feel free to contact me at [@IvoPetkovCom](https://twitter.com/IvoPetkovCom) or [ivopetkov.com](https://ivopetkov.com).
