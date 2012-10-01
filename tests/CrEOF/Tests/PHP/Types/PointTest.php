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
use CrEOF\PHP\Types\Point;

/**
 * Point object tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class PointTest extends \PHPUnit_Framework_TestCase
{
    public function testGoodPoints()
    {
        // Test numeric parameters
        $point1 = new Point(42.6525793, -73.7562317);
        $this->assertEquals(42.6525793, $point1->getLatitude());
        $this->assertEquals(-73.7562317, $point1->getLongitude());

        // Test good string parameters
        $point2 = new Point('40:26:46N', '79:56:55W');
        $this->assertEquals(40.446111111111, $point2->getLatitude());
        $this->assertEquals(-79.948611111111, $point2->getLongitude());

        $point3 = new Point('40°26\'46"N', '79°56\'55"W');
        $this->assertEquals(40.446111111111, $point3->getLatitude());
        $this->assertEquals(-79.948611111111, $point3->getLongitude());

        $point4 = new Point('40° 26\' 46" N', '79° 56\' 55" W');
        $this->assertEquals(40.446111111111, $point4->getLatitude());
        $this->assertEquals(-79.948611111111, $point4->getLongitude());

        $point5 = new Point('40°26′46″N', '79°56′55″W');
        $this->assertEquals(40.446111111111, $point5->getLatitude());
        $this->assertEquals(-79.948611111111, $point5->getLongitude());

        $point6 = new Point('40° 26′ 46″ N', '79° 56′ 55″ W');
        $this->assertEquals(40.446111111111, $point6->getLatitude());
        $this->assertEquals(-79.948611111111, $point6->getLongitude());

        $point7 = new Point('40:26:46.543N', '79:56:55.832W');
        $this->assertEquals(40.446261944444, $point7->getLatitude());
        $this->assertEquals(-79.948842222222, $point7->getLongitude());

        $point7 = new Point('33:27:0N', '112:4:0W');
        $this->assertEquals(33.45, $point7->getLatitude());
        $this->assertEquals(-112.06666666667, $point7->getLongitude());
    }

    public function testBadPoint1()
    {
        // Test bad string parameters - invalid latitude direction
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46Q', '100:56:55W');
    }

    public function testBadPoint2()
    {
        // Test bad string parameters - latitude greater that 90
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('92:26:46N', '79:56:55W');
    }

    public function testBadPoint3()
    {
        // Test bad string parameters - latitude minutes greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:64:46N', '108:42:55W');
    }

    public function testBadPoint4()
    {
        // Test bad string parameters - latitude seconds greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:23:75N', '108:42:55W');
    }

    public function testBadPoint5()
    {
        // Test bad string parameters - invalid longitude direction
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '100:56:55P');
    }

    public function testBadPoint6()
    {
        // Test bad string parameters - longitude greater than 180
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '190:56:55W');
    }

    public function testBadPoint7()
    {
        // Test bad string parameters - longitude minutes greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '108:62:55W');
    }

    public function testBadPoint8()
    {
        // Test bad string parameters - longitude seconds greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '108:53:94W');
    }

    public function testToString()
    {
        $point  = new Point(42.6525793, -73.7562317);
        $result = (string) $point;

        $this->assertEquals('Point(42.6525793 -73.7562317)', $result);
    }
}
