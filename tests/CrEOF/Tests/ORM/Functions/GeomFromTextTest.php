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
use CrEOF\PHP\Types\LineString;
use CrEOF\Tests\Fixtures\GeometryEntity;
use CrEOF\Tests\OrmTest;

/**
 * GeomFromText DQL function tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GeomFromTextTest extends OrmTest
{
    public function testPoint()
    {
        $entity1 = new GeometryEntity();

        $entity1->setGeometry(new Point(5, 5));
        $this->_em->persist($entity1);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT g FROM CrEOF\Tests\Fixtures\GeometryEntity g WHERE g.geometry = GeomFromText(:geometry)');

        $query->setParameter('geometry', new Point(5, 5));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }

    public function testLineString()
    {
        $value = array(
            new Point(0, 0),
            new Point(5, 5),
            new Point(10, 10)
        );

        $entity1 = new GeometryEntity();

        $entity1->setGeometry(new LineString($value));
        $this->_em->persist($entity1);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT g FROM CrEOF\Tests\Fixtures\GeometryEntity g WHERE g.geometry = GeomFromText(:geometry)');

        $query->setParameter('geometry', new LineString($value));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }
}
