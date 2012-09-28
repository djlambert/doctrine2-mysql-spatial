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
use CrEOF\Tests\Fixtures\PointEntity;
use CrEOF\Tests\OrmTest;

/**
 * X DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class XTest extends OrmTest
{
    public function testX()
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

        $query  = $this->_em->createQuery('SELECT X(p.point) FROM CrEOF\Tests\Fixtures\PointEntity p');
        $result = $query->getResult();

        $this->assertEquals(0, $result[0][1]);
        $this->assertEquals(5, $result[1][1]);
        $this->assertEquals(10, $result[2][1]);
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PointEntity p WHERE X(p.point) = 5');
        $result = $query->getResult();

        $this->assertEquals($entity2, $result[0]);
    }
}
