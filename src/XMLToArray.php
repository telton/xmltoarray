<?php

namespace Telton\XMLToArray;

class XMLToArray
{
    /**
     * Named constructor to convert an XML string to an array.
     *
     * @author Tyler Elton <telton007@gmail.com>
     * @param  string  $xml
     * @param  boolean $outputRoot
     * @return array
     */
    public static function convert(string $xml, bool $outputRoot = false): array
    {
        $array = self::xmlToArray($xml);

        // If we don't want the root node, remove it.
        if (!$outputRoot && array_key_exists('root', $array)) {
            unset($array['root']);

            if (array_key_exists('attributes', $array)) {
                // Unset the root's attributes too, if there are any.
                unset($array['attributes']);
            }
        }

        return $array;
    }

    /**
     * Convert an XML string to an array.
     *
     * @author Tyler Elton <telton007@gmail.com>
     * @param  string $xml
     * @return array
     */
    protected static function XMLToArray(string $xml): array
    {
        // Create a new DOM document and load the XML.
        $document = new \DOMDocument();
        $document->loadXml($xml);

        $documentRoot = $document->documentElement;

        // Convert all of the DOM nodes to an array.
        $output = self::DOMNodeToArray($documentRoot);
        $output['root'] = $documentRoot->tagName;

        return $output;
    }

    /**
     * Convert XML DOM nodes into an array.
     *
     * @author Tyler Elton <telton007@gmail.com>
     * @param  $node
     * @return array|string
     */
    protected static function DOMNodeToArray($node)
    {
        $output = [];

        switch ($node->nodeType) {
            case XML_ELEMENT_NODE:
                for ($i = 0, $length = $node->childNodes->length; $i < $length; $i++) {
                    $child = $node->childNodes->item($i);
                    $value = self::DOMNodeToArray($child);

                    if (isset($child->tagName)) {
                        $tag = $child->tagName;

                        if (!isset($output[$tag])) {
                            $output[$tag] = [];
                        }

                        $output[$tag][] = $value;
                    } elseif ($value || $value === '0') {
                        $output = (string) $value;
                    }
                }

                if ($node->attributes->length && !is_array($output)) {
                    // The node has attributes, but isn't an array, so change output to an array.
                    $output = ['content' => $output];
                }

                if (is_array($output)) {
                    // The node has attributes and is an array.
                    if ($node->attributes->length) {
                        $attributes = [];

                        foreach ($node->attributes as $attributeName => $attributeNode) {
                            $attributes[$attributeName] = $attributeNode->value;
                        }

                        $output['attributes'] = $attributes;
                    }

                    foreach ($output as $tag => $value) {
                        if (is_array($value) && count($value) === 1 && $tag !== 'attributes') {
                            $output[$tag] = $value[0];
                        }
                    }
                }

                break;
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
        }

        return $output;
    }
}
