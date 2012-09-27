<?php

namespace CrEOF\Tests\DBAL\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\PHP\Types\LineString;
use CrEOF\Tests\Fixtures\GeometryEntity;
use CrEOF\Tests\OrmTest;

class GeomFromTextTest extends OrmTest
{
    public function testPoint()
    {
        $entity1 = new GeometryEntity();

        $entity1->setGeometry(new Point(5, 5));
        $this->_em->persist($entity1);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT g FROM CrEOF\Tests\Fixtures\GeometryEntity g WHERE g.geometry = GeomFromText(:geometry)');

        $query->setParameter('geometry', new Point(5, 5));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }

    public function testLineString()
    {
        $value = array(
            new Point(0, 0),
            new Point(5, 5),
            new Point(10, 10)
        );

        $entity1 = new GeometryEntity();

        $entity1->setGeometry(new LineString($value));
        $this->_em->persist($entity1);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT g FROM CrEOF\Tests\Fixtures\GeometryEntity g WHERE g.geometry = GeomFromText(:geometry)');

        $query->setParameter('geometry', new LineString($value));

        $result = $query->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals($entity1, $result[0]);
    }
}
