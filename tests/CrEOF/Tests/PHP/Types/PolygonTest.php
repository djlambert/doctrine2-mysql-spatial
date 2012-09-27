<?php

namespace CrEOF\Tests\PHP\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Polygon;
use CrEOF\PHP\Types\Point;

class PolygonTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyPolygon()
    {
        $lineString = new Polygon(array());

        $this->assertEmpty($lineString->getPoints());
    }

    public function testGoodSolidPolygon()
    {
        $points = array(
            new Point(0, 0),
            new Point(10, 0),
            new Point(10, 10),
            new Point(0, 10),
            new Point(0, 0)
        );

        $polygon = new Polygon($points);

        $this->assertEquals($points, $polygon->getPoints());
    }

    public function testGoodPolygonRing()
    {
        $points = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            ),
            array(
                new Point(5, 5),
                new Point(7, 5),
                new Point(7, 7),
                new Point(5, 7),
                new Point(5, 5)
            )
        );

        $polygon = new Polygon($points);

        $this->assertEquals($points, $polygon->getPoints());
    }

    public function testBadPolygon()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');

        $points = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            ),
            new Point(5, 5),
            new Point(7, 5)
        );

        $polygon = new Polygon($points);
    }
    public function testToString()
    {
        $points = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            ),
            array(
                new Point(5, 5),
                new Point(7, 5),
                new Point(7, 7),
                new Point(5, 7),
                new Point(5, 5)
            )
        );
        $polygon = new Polygon($points);
        $result = (string) $polygon;

        $this->assertEquals('POLYGON((0 0, 10 0, 10 10, 0 10, 0 0), (5 5, 7 5, 7 7, 5 7, 5 5))', $result);
    }
}
