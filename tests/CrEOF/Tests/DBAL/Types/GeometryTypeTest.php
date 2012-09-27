<?php

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\GeometryEntity;

/**
 * Test GeometryType class
 */
class GeometryTypeTest extends OrmTest
{
    public function testNullGeometry()
    {
        $entity = new GeometryEntity();

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::GEOMETRY_ENTITY)->find($id);

        $this->assertEquals($entity, $queryEntity);
    }

    public function testPointGeometry()
    {
        $entity = new GeometryEntity();

        $entity->setGeometry(new Point(42.6525793, -73.7562317));
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::GEOMETRY_ENTITY)->find($id);

        $this->assertEquals($entity, $queryEntity);
    }

    public function testLineStringGeometry()
    {
        $entity = new GeometryEntity();

        $entity->setGeometry(new LineString(
            array(
                 new Point(42.6525793, -73.7562317),
                 new Point(-24.6525793, 37.7562317)
            ))
        );
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::GEOMETRY_ENTITY)->find($id);

        $this->assertEquals($entity, $queryEntity);
    }
}
