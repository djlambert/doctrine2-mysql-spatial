<?php

namespace CrEOF\Tests\ORM\Functions;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\LineString;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\OrmTest;
use CrEOF\Tests\Fixtures\LineStringEntity;

/**
 * Test LineString DWL function
 */
class LineStringTest extends OrmTest
{
    public function testLineStringNestedFunction()
    {
         $p1 = new Point(42.6525793, -73.7562317);
         $p2 = new Point(48.5793, 34.034);
         $p3 = new Point(-75.371, 87.90424);

        $entity = new LineStringEntity();

        $entity->setLineString(new LineString(
            array(
                 $p1,
                 $p2,
                 $p3
            ))
        );

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT l FROM CrEOF\Tests\Fixtures\LineStringEntity l WHERE l.lineString = LineString(GeomFromText(:p1), GeomFromText(:p2), GeomFromText(:p3))');

        $query->setParameter('p1', $p1);
        $query->setParameter('p2', $p2);
        $query->setParameter('p3', $p3);

        $result = $query->getResult();

        $this->assertEquals($entity, $result[0]);
    }

    public function testLineStringParameter()
    {
        $p1 = new Point(42.6525793, -73.7562317);
        $p2 = new Point(48.5793, 34.034);
        $p3 = new Point(-75.371, 87.90424);

        $entity = new LineStringEntity();

        $entity->setLineString(new LineString(
            array(
                 $p1,
                 $p2,
                 $p3
            ))
        );

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $query = $this->_em->createQuery('SELECT l FROM CrEOF\Tests\Fixtures\LineStringEntity l WHERE l.lineString = LineString(:p1, :p2, :p3)');

        $query->setParameter('p1', $p1);
        $query->setParameter('p2', $p2);
        $query->setParameter('p3', $p3);

        $result = $query->getResult();

        $this->assertEquals($entity, $result[0]);
    }
}
