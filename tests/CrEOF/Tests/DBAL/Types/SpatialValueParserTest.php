<?php

namespace CrEOF\Tests\DBAL\Types;

use CrEOF\DBAL\Types\SpatialValueParser;

/**
 * Test SpatialValueParser class
 */
class SpatialValueParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSinglePoint()
    {
        $parser = new SpatialValueParser();
        $value  = array(
            array(5.45, -23.5)
        );
        $string = '(5.45 -23.5)';
        $result = $parser->parse($string);

        $this->assertEquals($value, $result);
    }

    public function testMultiPoint()
    {
        $parser = new SpatialValueParser();
        $value  = array(
            array(5.45, -23.5),
            array(-4.2, 99),
            array(23, 57.2345)
        );
        $string = '(5.45 -23.5, -4.2 99, 23 57.2345)';
        $result = $parser->parse($string);

        $this->assertEquals($value, $result);
    }

    public function testNestedMultiPoint()
    {
        $parser = new SpatialValueParser();
        $value  = array(
            array(
                array(5.45, -23.5),
                array(-4.2, 99),
                array(23, 57.2345)
                ),
            array(
                array(5.45, -23.5),
                array(-4.2, 99),
                array(23, 57.2345)
            )
        );
        $string = '((5.45 -23.5, -4.2 99, 23 57.2345), (5.45 -23.5, -4.2 99, 23 57.2345))';
        $result = $parser->parse($string);

        $this->assertEquals($value, $result);
    }
}
