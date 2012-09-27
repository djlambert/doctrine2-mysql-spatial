<?php

namespace CrEOF\Tests\DBAL\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\PHP\Types\Polygon;
use CrEOF\Tests\Fixtures\GeometryEntity;
use CrEOF\Tests\OrmTest;

class GeometryTypeTest extends OrmTest
{
    public function testGeometryType()
    {
        $entity1 = new GeometryEntity();

        $this->_em->persist($entity1);

        $entity2 = new GeometryEntity();

        $entity2->setGeometry(new Point(5, 5));
        $this->_em->persist($entity2);

        $value = array(
            new Point(0, 0),
            new Point(5, 5),
            new Point(10, 10)
        );
        $entity3 = new GeometryEntity();

        $entity3->setGeometry(new LineString($value));
        $this->_em->persist($entity3);

        $value = array(
            array(
                new Point(0, 0),
                new Point(10, 0),
                new Point(10, 10),
                new Point(0, 10),
                new Point(0, 0)
            )
        );
        $entity4 = new GeometryEntity();

        $entity4->setGeometry(new Polygon($value));
        $this->_em->persist($entity4);
        $this->_em->flush();
        $this->_em->clear();

        $query  = $this->_em->createQuery('SELECT GeometryType(g.geometry) FROM CrEOF\Tests\Fixtures\GeometryEntity g');
        $result = $query->getResult();

        $this->assertCount(4, $result);
        $this->assertNull($result[0][1]);
        $this->assertEquals('POINT', $result[1][1]);
        $this->assertEquals('LINESTRING', $result[2][1]);
        $this->assertEquals('POLYGON', $result[3][1]);
    }
}
