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
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\LineStringEntity;

/**
 * NumPoints DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class NumPointsTest extends OrmTest
{
    public function testNumPoints()
    {
        $entity1 = new LineStringEntity();

        $entity1->setLineString(new LineString(
            array(
                 new Point(42.6525793, -73.7562317),
                 new Point(48.5793, 34.034),
                 new Point(-75.371, 87.90424)
            ))
        );
        $this->_em->persist($entity1);

        $entity2 = new LineStringEntity();

        $entity2->setLineString(new LineString(
            array(
                 new Point(42.6525793, -73.7562317),
                 new Point(48.5793, 34.034),
                 new Point(-75.371, 87.90424),
                 new Point(56.74, 2.053)
            ))
        );
        $this->_em->persist($entity2);

        $entity3 = new LineStringEntity();

        $entity3->setLineString(new LineString(
            array(
                 new Point(42.6525793, -73.7562317),
                 new Point(56.74, 2.053)
            ))
        );
        $this->_em->persist($entity3);
        $this->_em->flush();
        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT NumPoints(l.lineString) FROM CrEOF\Tests\Fixtures\LineStringEntity l');
        $result = $query->getResult();

        $this->assertEquals(3, $result[0][1]);
        $this->assertEquals(4, $result[1][1]);
        $this->assertEquals(2, $result[2][1]);
        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT l FROM CrEOF\Tests\Fixtures\LineStringEntity l WHERE NumPoints(l.lineString) = 4');
        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity2, $result[0]);
    }
}
