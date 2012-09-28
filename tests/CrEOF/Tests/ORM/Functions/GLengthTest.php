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
use CrEOF\Tests\Fixtures\PointEntity;
use CrEOF\Tests\Fixtures\LineStringEntity;
use CrEOF\Tests\OrmTest;

/**
 * GLength DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GLengthTest extends OrmTest
{
    public function testPointGLength()
    {
        $entity1 = new PointEntity();

        $entity1->setPoint(new Point(0, 0));
        $this->_em->persist($entity1);

        $entity2 = new PointEntity();

        $entity2->setPoint(new Point(5, 5));
        $this->_em->persist($entity2);

        $entity3 = new PointEntity();

        $entity3->setPoint(new Point(10, 10));
        $this->_em->persist($entity3);

        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PointEntity p WHERE GLength(LineString(p.point, :point)) > 10');

        $query->setParameter('point', new Point(10, 10));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }

    public function testGLengthParameter()
    {
        $points = array(
            new Point(0, 0),
            new Point(1, 1),
            new Point(2, 2),
            new Point(3, 3)
        );
        $lineString = new LineString($points);
        $entity     = new LineStringEntity();

        $entity->setLineString(new LineString(
            array(
                 new Point(0, 0),
                 new Point(1, 1),
                 new Point(2, 2)
            ))
        );

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT l FROM CrEOF\Tests\Fixtures\LineStringEntity l WHERE GLength(:p1) > GLength(l.lineString)');

        $query->setParameter('p1', $lineString);

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity, $result[0]);
    }
}
