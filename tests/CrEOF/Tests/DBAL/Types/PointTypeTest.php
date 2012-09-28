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

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PointEntity;

/**
 * PointType tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class PointTypeTest extends OrmTest
{
    public function testNullPoint()
    {
        $entity = new PointEntity();
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POINT_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }

    public function testPoint()
    {
        $entity = new PointEntity();
        $entity->setPoint(new Point(42.6525793, -73.7562317));
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POINT_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }

    public function testFindByPoint()
    {
        $entity = new PointEntity();
        $entity->setPoint(new Point(42.6525793, -73.7562317));
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $result = $this->_em->getRepository(self::POINT_ENTITY)->findByPoint(new Point(42.6525793, -73.7562317));
        $this->assertEquals($entity, $result[0]);
    }
}
