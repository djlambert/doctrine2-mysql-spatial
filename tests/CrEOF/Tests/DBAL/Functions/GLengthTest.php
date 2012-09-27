<?php

namespace CrEOF\Tests\DBAL\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\Fixtures\PositionEntity;
use CrEOF\Tests\OrmTest;

class GLengthTest extends OrmTest
{
    public function testPointGLength()
    {
        $entity1 = new PositionEntity();
        $entity1->setPoint(new Point(0, 0));
        $this->_em->persist($entity1);

        $entity2 = new PositionEntity();
        $entity2->setPoint(new Point(5, 5));
        $this->_em->persist($entity2);

        $entity3 = new PositionEntity();
        $entity3->setPoint(new Point(10, 10));
        $this->_em->persist($entity3);

        $this->_em->flush();

        $id1 = $entity1->getId();
        $id2 = $entity2->getId();
        $id2 = $entity3->getId();

        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PositionEntity p WHERE GLength(p.point, GeomFromText(:point)) > 10');

        $query->setParameter('point', new Point(10, 10));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }
}
