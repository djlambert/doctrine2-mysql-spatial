<?php

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\LineStringEntity;

/**
 * Test LineStringType class
 */
class LineStringTypeTest extends OrmTest
{
    public function testNullLineString()
    {
        $entity = new LineStringEntity();

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::LINESTRING_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }

    public function testLineString()
    {
        $entity = new LineStringEntity();

        $entity->setLineString(new LineString(
            array(
                 new Point(42.6525793, -73.7562317),
                 new Point(48.5793, 34.034),
                 new Point(-75.371, 87.90424)
            ))
        );

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::LINESTRING_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }
}
