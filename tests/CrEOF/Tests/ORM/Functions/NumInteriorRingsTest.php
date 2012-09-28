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

namespace CrEOF\Tests\ORM\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\PHP\Types\Polygon;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PolygonEntity;

/**
 * NumInteriorRings DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class NumInteriorRingsTest extends OrmTest
{
    public function testNumInteriorRings()
    {
        $entity1 = new PolygonEntity();
        $points = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            )
        );

        $entity1->setPolygon(new Polygon($points));
        $this->_em->persist($entity1);

        $entity2 = new PolygonEntity();
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

        $entity2->setPolygon(new Polygon($points));
        $this->_em->persist($entity2);

        $entity3 = new PolygonEntity();
        $points = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 20),
                new Point(0, 20),
                new Point(10, 10),
                new Point(0, 0)
            )
        );

        $entity3->setPolygon(new Polygon($points));
        $this->_em->persist($entity3);

        $entity4 = new PolygonEntity();
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
            ),
            array(
                new Point(2, 2),
                new Point(4, 2),
                new Point(4, 4),
                new Point(2, 4),
                new Point(2, 2)
            )
        );

        $entity4->setPolygon(new Polygon($points));
        $this->_em->persist($entity4);
        $this->_em->flush();
        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT NumInteriorRings(p.polygon) FROM CrEOF\Tests\Fixtures\PolygonEntity p');
        $result = $query->getResult();

        $this->assertEquals(0, $result[0][1]);
        $this->assertEquals(1, $result[1][1]);
        $this->assertEquals(0, $result[2][1]);
        $this->assertEquals(2, $result[3][1]);
        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PolygonEntity p WHERE NumInteriorRings(p.polygon) = 1');
        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity2, $result[0]);
    }
}
