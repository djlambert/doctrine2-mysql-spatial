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

namespace CrEOF\Tests\ORM\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\PHP\Types\Polygon;
use CrEOF\Tests\Fixtures\GeometryEntity;
use CrEOF\Tests\OrmTest;

/**
 * GeometryType DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GeometryTypeTest extends OrmTest
{
    public function testGeometryType()
    {
        $entity1 = new GeometryEntity();

        $this->_em->persist($entity1);

        $entity2 = new GeometryEntity();

        $entity2->setGeometry(new Point(5, 5));
        $this->_em->persist($entity2);

        $value = array(
            new Point(0, 0),
            new Point(5, 5),
            new Point(10, 10)
        );
        $entity3 = new GeometryEntity();

        $entity3->setGeometry(new LineString($value));
        $this->_em->persist($entity3);

        $value = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            )
        );
        $entity4 = new GeometryEntity();

        $entity4->setGeometry(new Polygon($value));
        $this->_em->persist($entity4);
        $this->_em->flush();
        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT GeometryType(g.geometry) FROM CrEOF\Tests\Fixtures\GeometryEntity g');
        $result = $query->getResult();

        $this->assertCount(4, $result);
        $this->assertNull($result[0][1]);
        $this->assertEquals('POINT', $result[1][1]);
        $this->assertEquals('LINESTRING', $result[2][1]);
        $this->assertEquals('POLYGON', $result[3][1]);
    }
}
