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
 * GeometryWalker tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GeometryWalkerTest extends OrmTest
{
    public function testGeometryWalker()
    {
        $lineString1 = array(
            new Point(0, 0),
            new Point(2, 2),
            new Point(5, 5)
        );
        $lineString2 = array(
            new Point(3, 3),
            new Point(4, 15),
            new Point(5, 22)
        );
        $entity1 = new LineStringEntity();

        $entity1->setLineString(new LineString($lineString1));
        $this->_em->persist($entity1);

        $entity2 = new LineStringEntity();

        $entity2->setLineString(new LineString($lineString2));
        $this->_em->persist($entity2);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT AsText(StartPoint(l.lineString)) FROM CrEOF\Tests\Fixtures\LineStringEntity l');
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'CrEOF\ORM\Query\GeometryWalker');

        $result = $query->getResult();
        $this->assertEquals(new Point(0, 0), $result[0][1]);
        $this->assertEquals(new Point(3, 3), $result[1][1]);

        $query = $this->_em->createQuery('SELECT AsText(Envelope(l.lineString)) FROM CrEOF\Tests\Fixtures\LineStringEntity l');
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'CrEOF\ORM\Query\GeometryWalker');

        $result = $query->getResult();
        $this->assertInstanceOf('CrEOF\PHP\Types\Polygon', $result[0][1]);
        $this->assertInstanceOf('CrEOF\PHP\Types\Polygon', $result[1][1]);
    }
}
