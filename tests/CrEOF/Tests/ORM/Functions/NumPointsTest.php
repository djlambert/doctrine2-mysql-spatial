<?php

namespace CrEOF\Tests\ORM\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\LineStringEntity;

/**
 * Test NumPoints DQL function
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
