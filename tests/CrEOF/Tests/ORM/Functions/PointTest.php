<?php

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\PointEntity;

/**
 * Test Point DQL function
 */
class PointTest extends OrmTest
{

    public function testPoint()
    {
        $entity = new PointEntity();
        $entity->setPoint(new Point(10.5, -5.5));
        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->getId();

        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PointEntity p WHERE p.point = Point(:p1, :p2)');
        $query->setParameter('p1', 10.5);
        $query->setParameter('p2', -5.5);

        $result = $query->getResult();

        $this->assertEquals($entity, $result[0]);
    }
}
