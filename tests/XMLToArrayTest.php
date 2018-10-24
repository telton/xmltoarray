<?php

namespace Telton\XmlToArray\Tests;

class XMLToArrayTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->xml = '<note>
          <to>Tove</to>
          <from>Jani</from>
          <heading>Reminder</heading>
          <body>Don\'t forget me this weekend!</body>
          </note>';
    }

    /** @test */
    public function it_converts_xml_to_array()
    {
        $convertedArray = \Telton\XMLToArray\XMLToArray::convert($this->xml);

        $this->assertTrue(is_array($convertedArray));
    }

    /** @test */
    public function if_the_xml_has_attribute_tags_it_still_converts_correctly()
    {
        $xmlWithAttributes = '<note type="reminder">
          <to value="name">Tove</to>
          <from value="name">Jani</from>
          <heading>Reminder</heading>
          <body type="text">Don\'t forget me this weekend!</body>
          </note>';

        $convertedArray = \Telton\XMLToArray\XMLToArray::convert($xmlWithAttributes);

        $this->assertTrue(is_array($convertedArray));
    }

    /** @test */
    public function the_converted_array_matches_the_xml_without_the_root_element()
    {
        $convertedArray = \Telton\XMLToArray\XMLToArray::convert($this->xml);

        $this->assertEquals([
          'to'      => 'Tove',
          'from'    => 'Jani',
          'heading' => 'Reminder',
          'body'    => 'Don\'t forget me this weekend!',
        ], $convertedArray);
    }

    /** @test */
    public function the_converted_array_matches_the_xml_with_the_root_element()
    {
        $convertedArray = \Telton\XMLToArray\XMLToArray::convert($this->xml, true);

        $this->assertEquals([
          'to'      => 'Tove',
          'from'    => 'Jani',
          'heading' => 'Reminder',
          'body'    => 'Don\'t forget me this weekend!',
          'root'    => 'note',
        ], $convertedArray);
    }

    /** @test */
    public function tag_attributes_are_properly_converted_without_the_root_element()
    {
        $xmlWithAttributes = '<note type="reminder">
          <to value="name">Tove</to>
          <from value="name">Jani</from>
          <heading>Reminder</heading>
          <body type="text">Don\'t forget me this weekend!</body>
          </note>';

        $convertedArray = \Telton\XMLToArray\XMLToArray::convert($xmlWithAttributes);

        $this->assertEquals([
          'to' => [
            'content'    => 'Tove',
            'attributes' => [
              'value' => 'name',
            ],
          ],
          'from' => [
            'content'    => 'Jani',
            'attributes' => [
              'value' => 'name',
            ],
          ],
          'heading' => 'Reminder',
          'body'    => [
            'content'    => 'Don\'t forget me this weekend!',
            'attributes' => [
              'type' => 'text',
            ],
          ],
        ], $convertedArray);
    }

    /** @test */
    public function tag_attributes_are_properly_converted_with_the_root_element()
    {
        $xmlWithAttributes = '<note type="reminder">
          <to value="name">Tove</to>
          <from value="name">Jani</from>
          <heading>Reminder</heading>
          <body type="text">Don\'t forget me this weekend!</body>
          </note>';

        $convertedArray = \Telton\XMLToArray\XMLToArray::convert($xmlWithAttributes, true);
        // die(var_dump($convertedArray));

        $this->assertEquals([
          'to' => [
            'content'    => 'Tove',
            'attributes' => [
              'value' => 'name',
            ],
          ],
          'from' => [
            'content'    => 'Jani',
            'attributes' => [
              'value' => 'name',
            ],
          ],
          'heading' => 'Reminder',
          'body'    => [
            'content'    => 'Don\'t forget me this weekend!',
            'attributes' => [
              'type' => 'text',
            ],
          ],
          'attributes' => [
            'type' => 'reminder',
          ],
          'root'       => 'note',
        ], $convertedArray);
    }
}
