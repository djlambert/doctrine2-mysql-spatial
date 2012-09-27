<?php

namespace CrEOF\Tests\ORM\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\PHP\Types\Polygon;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PolygonEntity;

/**
 * Test NumInteriorRings DQL function
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
