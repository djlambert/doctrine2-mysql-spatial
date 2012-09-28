<?php
/**
 * Copyright (C) 2012 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Tests\PHP\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Polygon;
use CrEOF\PHP\Types\Point;

/**
 * Polygon object tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class PolygonTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyPolygon()
    {
        $lineString = new Polygon(array());

        $this->assertEmpty($lineString->getPolygons());
    }

    public function testGoodSolidPolygon()
    {
        $lineStrings = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            )
        );

        $polygon = new Polygon($lineStrings);

        $this->assertEquals($lineStrings, $polygon->getPolygons());
    }

    public function testGoodPolygonRing()
    {
        $lineStrings = array(
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

        $polygon = new Polygon($lineStrings);

        $this->assertEquals($lineStrings, $polygon->getPolygons());
    }

    public function testBadPolygon()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');

        $lineStrings = array(
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

        $polygon = new Polygon($lineStrings);
    }
    public function testToString()
    {
        $lineStrings = array(
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
        $polygon = new Polygon($lineStrings);
        $result  = (string) $polygon;

        $this->assertEquals('POLYGON((0 0, 10 0, 10 10, 0 10, 0 0), (5 5, 7 5, 7 7, 5 7, 5 5))', $result);
    }
}
