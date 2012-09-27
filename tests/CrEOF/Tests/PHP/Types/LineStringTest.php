<?php

namespace CrEOF\Tests\PHP\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;

class LineStringTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyLineString()
    {
        $lineString = new LineString(array());

        $this->assertEmpty($lineString->getPoints());
    }

    public function testGoodLineString()
    {
        $points = array(
            new Point(42.6525793, -73.7562317),
            new Point(42.6525793, -73.7562317),
            new Point(42.6525793, -73.7562317),
            new Point(42.6525793, -73.7562317)
        );

        $lineString = new LineString($points);

        $this->assertCount(4, $lineString->getPoints());
    }

    public function testBadLineString()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');

        $lineString = new LineString(array(1, 2, 3 ,4));
    }

    public function testToString()
    {
        $points     = array(
            new Point(0, 0),
            new Point(10, 0),
            new Point(10, 10),
            new Point(0, 10)
        );
        $lineString = new LineString($points);
        $result     = (string) $lineString;

        $this->assertEquals('LINESTRING(0 0, 10 0, 10 10, 0 10)', $result);
    }
}
