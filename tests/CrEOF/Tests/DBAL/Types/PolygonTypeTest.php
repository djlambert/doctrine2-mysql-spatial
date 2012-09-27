<?php

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Polygon;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PolygonEntity;

/**
 * Test PolygonType class
 */
class PolygonTypeTest extends OrmTest
{
    public function testNullPolygon()
    {
        $entity = new PolygonEntity();

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POLYGON_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }

    public function testPolygon()
    {
        $entity = new PolygonEntity();
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

        $entity->setPolygon(new Polygon($points));

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POLYGON_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }

    public function testBadPolygon()
    {
        $entity = new PolygonEntity();
        $points = array(
            new Point(0, 0),
            new Point(10, 0),
            new Point(10, 10),
            new Point(0, 10),
        );


        $entity->setPolygon(new Polygon($points));

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POLYGON_ENTITY)->find($id);
        $this->assertNull($queryEntity->getPolygon());
    }
}
