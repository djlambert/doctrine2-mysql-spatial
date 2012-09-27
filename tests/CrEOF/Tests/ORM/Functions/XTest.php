<?php

namespace CrEOF\Tests\ORM\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\Fixtures\PointEntity;
use CrEOF\Tests\OrmTest;

class XTest extends OrmTest
{
    public function testX()
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

        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT X(p.point) FROM CrEOF\Tests\Fixtures\PointEntity p');
        $result = $query->getResult();

        $this->assertEquals(0, $result[0][1]);
        $this->assertEquals(5, $result[1][1]);
        $this->assertEquals(10, $result[2][1]);
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT p FROM CrEOF\Tests\Fixtures\PointEntity p WHERE X(p.point) = 5');
        $result = $query->getResult();

        $this->assertEquals($entity2, $result[0]);
    }
}
