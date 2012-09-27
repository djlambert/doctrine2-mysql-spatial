<?php

namespace CrEOF\Tests\DBAL\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\Fixtures\PointEntity;
use CrEOF\Tests\OrmTest;

class GLengthTest extends OrmTest
{
    public function testPointGLength()
    {
        $entity1 = new PointEntity();
        $entity1->setPoint(new Point(0, 0));
        $this->_em->persist($entity1);

        $entity2 = new PointEntity();
        $entity2->setPoint(new Point(5, 5));
        $this->_em->persist($entity2);

        $entity3 = new PointEntity();
        $entity3->setPoint(new Point(10, 10));
        $this->_em->persist($entity3);

        $this->_em->flush();

        $id1 = $entity1->getId();
        $id2 = $entity2->getId();
        $id2 = $entity3->getId();

        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PointEntity p WHERE GLength(p.point, GeomFromText(:point)) > 10');

        $query->setParameter('point', new Point(10, 10));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }
}
