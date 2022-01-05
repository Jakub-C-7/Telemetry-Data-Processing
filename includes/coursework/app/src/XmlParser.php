<?php

namespace Coursework;

/**
 * XmlParser class parses a given XML string.
 *
 * The class parses xml strings by locating elements within the XML (using tags) and returning each element as a
 * separate arrays for retrieving and separation convenience.
 *
 * @author Jakub Chamera
 * Date: 17/11/2021
 */
class XmlParser
{
    /**
     * An instance of the XmlParser
     */
    private $xmlParser;

    /**
     * @var array Array holds extracted data
     */
    private $parsedData;

    /**
     * Stores the current element name.
     */
    private $elementName;

    /**
     * Temporarily stores the XML tag attributes and values.
     */
    private $temporaryAttributes;

    /**
     * The XML string to be parsed.
     */
    private $xmlStringToParse;

    /**
     * Contruct method clears the parsed data array.
     */
    public function __construct()
    {
        $this->parsedData = [];
    }

    /**
     * Destruct method releases retained memory from the parser.
     */
    public function __destruct()
    {
        xml_parser_free($this->xmlParser);
    }

    /**
     * Resets and clears an XmlParser
     * @return void
     */
    public function resetXmlParser()
    {
        $this->xmlParser = null;
    }

    /**
     * Sets the xml string input to be parsed by the XmlParser.
     * @param $xmlStringToParse -The XML String to be parsed.
     * @return void
     */
    public function setXmlStringToParse($xmlStringToParse)
    {
        $this->xmlStringToParse = $xmlStringToParse;
    }

    /**
     * Retrieve the parsed data from the parsedData array.
     * @return array An array containing all parsed and separated data.
     */
    public function getParsedData()
    {
        return $this->parsedData;
    }

    /**
     * Create a new instance of an XmlParser and free any parsers that may exist.
     * @return void
     */
    private function createXmlParser()
    {
        if ($this->xmlParser !== null) {
            xml_parser_free($this->xmlParser);
        }

        $this->xmlParser = xml_parser_create();

        xml_set_object($this->xmlParser, $this);

        xml_set_element_handler($this->xmlParser, "openElement", "closeElement");

        xml_set_character_data_handler($this->xmlParser, "processElementData");
    }

    /**
     * Parses an XML string and returns it as an array of separated string elements.
     * @param string $xmlStringToParse A string to be parsed.
     * @return array Returns an array containing parsed and separated elements.
     */
    public function parseXmlArray(string $xmlStringToParse): array
    {
        $this->createXmlParser();

        $this->parsedData = [];
        $this->temporaryAttributes = [];

        xml_parse($this->xmlParser, $xmlStringToParse);

        return $this->parsedData;
    }

    /**
     * Steps through the element tags of the XML string.
     * @return void
     */
    private function parseTheDataString()
    {
        xml_parse($this->xmlParser, $this->xmlStringToParse);
    }

    /**
     * Process an open element event and store the tag name. Extract the attribute names and values if they exist.
     * @param $parser -The instance of the XmlParser.
     * @param $element_name -The name of the xml element (tag).
     * @param $attributes -The attributes from the xml element.
     * @return void
     */
    private function openElement($parser, $element_name, $attributes)
    {
        $this->elementName = $element_name;
        if (sizeof($attributes) > 0)
        {
            foreach ($attributes as $att_name => $att_value)
            {
                $tag_att = $element_name . "." . $att_name;
                $this->temporaryAttributes[$tag_att] = $att_value;
            }
        }
    }

    /**
     * Processes the data from a parsed element.
     * @param $parser -The instance of the xml parser.
     * @param $elementData -The element data to be parsed.
     * @return void
     */
    private function processElementData($parser, $elementData)
    {
        if (array_key_exists($this->elementName, $this->parsedData) === false)
        {
            $this->parsedData[$this->elementName] = $elementData;
            if (sizeof($this->temporaryAttributes) > 0)
            {
                foreach ($this->temporaryAttributes as $tagAttName => $tagAttValue)
                {
                    $this->parsedData[$tagAttName] = $tagAttValue;
                }
            }
        }
    }

    /**
     * Process a close element event.
     * @param $parser -The parser instance to be closed.
     * @param $elementName -The name of the element.
     * @return void
     */
    private function closeElement($parser, $elementName)
    {
        // do nothing here
    }
}