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
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\LineStringEntity;

/**
 * LineString DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class LineStringTest extends OrmTest
{
    public function testLineStringNestedFunction()
    {
         $p1 = new Point(42.6525793, -73.7562317);
         $p2 = new Point(48.5793, 34.034);
         $p3 = new Point(-75.371, 87.90424);

        $entity = new LineStringEntity();

        $entity->setLineString(new LineString(
            array(
                 $p1,
                 $p2,
                 $p3
            ))
        );

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT l FROM CrEOF\Tests\Fixtures\LineStringEntity l WHERE l.lineString = LineString(GeomFromText(:p1), GeomFromText(:p2), GeomFromText(:p3))');

        $query->setParameter('p1', $p1);
        $query->setParameter('p2', $p2);
        $query->setParameter('p3', $p3);

        $result = $query->getResult();

        $this->assertEquals($entity, $result[0]);
    }

    public function testLineStringParameter()
    {
        $p1 = new Point(42.6525793, -73.7562317);
        $p2 = new Point(48.5793, 34.034);
        $p3 = new Point(-75.371, 87.90424);

        $entity = new LineStringEntity();

        $entity->setLineString(new LineString(
            array(
                 $p1,
                 $p2,
                 $p3
            ))
        );

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT l FROM CrEOF\Tests\Fixtures\LineStringEntity l WHERE l.lineString = LineString(:p1, :p2, :p3)');

        $query->setParameter('p1', $p1);
        $query->setParameter('p2', $p2);
        $query->setParameter('p3', $p3);

        $result = $query->getResult();

        $this->assertEquals($entity, $result[0]);
    }
}
