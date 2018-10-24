# XML to Array

[![Build Status](https://travis-ci.org/telton/xmltoarray.svg?branch=master)](https://travis-ci.org/telton/xmltoarray)

This is a PHP package that converts an XML string to an array.

To use:

```php
/**
 * XML Structure:
 *
 * <tag>
 *   <announcement>This is awesome!</announcement>
 *   <author>Tyler Elton</author>
 * </tag>
 **/

$array = \Telton\XMLToArray\XMLToArray::convert($xml);

/**
 * Converted array:
 *
 * [
 *   'announcement' => 'This is awesome!',
 *   'author'       => 'Tyler Elton'
 * ]
 **/
```

There is an optional flag in `convert()` that will add the root tag as well:

```php
/**
 * XML Structure:
 *
 * <tag>
 *   <announcement>This is awesome!</announcement>
 *   <author>Tyler Elton</author>
 * </tag>
 **/

$array = \Telton\XMLToArray\XMLToArray::convert($xml, true);

/**
 * Converted array:
 *
 * [
 *   'announcement' => 'This is awesome!',
 *   'author'       => 'Tyler Elton',
 *   'root'         => 'tag'
 * ]
 **/
```

If the XML has attribute tags, it will convert them like this:

```php
/**
 * XML Structure:
 *
 * <tag type="announcement">
 *   <announcement>This is awesome!</announcement>
 *   <author role="developer">Tyler Elton</author>
 * </tag>
 **/

$array = \Telton\XMLToArray\XMLToArray::convert($xml, true);

/**
 * Converted array:
 *
 * [
 *   'announcement' => 'This is awesome!',
 *   'author' => [
 *      'value' => 'Tyler Elton',
 *      'role'  => 'developer'
 *    ],
 *   'root'         => 'tag',
 *   'type'         => 'announcement'
 * ]
 **/
```
