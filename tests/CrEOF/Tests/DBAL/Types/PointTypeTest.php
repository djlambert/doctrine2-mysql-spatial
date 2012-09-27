<?php

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PositionEntity;

/**
 * Test PointType class
 */
class PointTypeTest extends OrmTest
{
    public function testNullPoint()
    {
        $entity = new PositionEntity();
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POSITION_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }

    public function testPoint()
    {
        $entity = new PositionEntity();
        $entity->setPoint(new Point(42.6525793, -73.7562317));
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $queryEntity = $this->_em->getRepository(self::POSITION_ENTITY)->find($id);
        $this->assertEquals($entity, $queryEntity);
    }
}
