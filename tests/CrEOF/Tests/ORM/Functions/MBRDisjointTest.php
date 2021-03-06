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
use CrEOF\PHP\Types\Point;
use CrEOF\PHP\Types\Polygon;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PolygonEntity;

/**
 * MBRDisjoint DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MBRDisjointTest extends OrmTest
{
    public function testMBRDisjointParameters()
    {
        $lineString1 = array(
            new Point(0, 0),
            new Point(10, 0),
            new Point(10, 10),
            new Point(0, 10),
            new Point(0, 0)
        );
        $lineString2 = array(
            new Point(5, 5),
            new Point(7, 5),
            new Point(7, 7),
            new Point(5, 7),
            new Point(5, 5)
        );
        $lineString3 = array(
            new Point(15, 15),
            new Point(17, 15),
            new Point(17, 17),
            new Point(15, 17),
            new Point(15, 15)
        );

        $entity1 = new PolygonEntity();

        $entity1->setPolygon(new Polygon(array($lineString1)));
        $this->_em->persist($entity1);

        $entity2 = new PolygonEntity();

        $entity2->setPolygon(new Polygon(array($lineString2)));
        $this->_em->persist($entity2);
        $this->_em->flush();

        $entity3 = new PolygonEntity();

        $entity3->setPolygon(new Polygon(array($lineString3)));
        $this->_em->persist($entity3);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PolygonEntity p WHERE MBRDisjoint(p.polygon, :p1) = 1');

        $query->setParameter('p1', new Polygon(array($lineString2)));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity3, $result[0]);
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PolygonEntity p WHERE MBRDisjoint(p.polygon, :p1) = 1');

        $query->setParameter('p1', new Polygon(array($lineString3)));

        $result = $query->getResult();

        $this->assertCount(2, $result);
        $this->assertEquals($entity1, $result[0]);
        $this->assertEquals($entity2, $result[1]);
    }

    public function testMBRDisjointNestedFunctions()
    {
        $lineString1 = array(
            new Point(0, 0),
            new Point(10, 0),
            new Point(10, 10),
            new Point(0, 10),
            new Point(0, 0)
        );
        $lineString2 = array(
            new Point(5, 5),
            new Point(7, 5),
            new Point(7, 7),
            new Point(5, 7),
            new Point(5, 5)
        );
        $lineString3 = array(
            new Point(15, 15),
            new Point(17, 15),
            new Point(17, 17),
            new Point(15, 17),
            new Point(15, 15)
        );

        $entity1 = new PolygonEntity();

        $entity1->setPolygon(new Polygon(array($lineString1)));
        $this->_em->persist($entity1);

        $entity2 = new PolygonEntity();

        $entity2->setPolygon(new Polygon(array($lineString2)));
        $this->_em->persist($entity2);
        $this->_em->flush();

        $entity3 = new PolygonEntity();

        $entity3->setPolygon(new Polygon(array($lineString3)));
        $this->_em->persist($entity3);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PolygonEntity p WHERE MBRDisjoint(p.polygon, GeomFromText(:p1)) = 1');

        $query->setParameter('p1', new Polygon(array($lineString2)));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity3, $result[0]);
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PolygonEntity p WHERE MBRDisjoint(p.polygon, GeomFromText(:p1)) = 1');

        $query->setParameter('p1', new Polygon(array($lineString3)));

        $result = $query->getResult();

        $this->assertCount(2, $result);
        $this->assertEquals($entity1, $result[0]);
        $this->assertEquals($entity2, $result[1]);
    }
}
