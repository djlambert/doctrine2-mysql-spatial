<?php

namespace CrEOF\Tests;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;
use CrEOF\Tests\Fixtures\Position;

class PointTypeTest extends OrmTest
{
    public function testNullPoint()
    {
        $position = new Position();
        $this->_em->persist($position);
        $this->_em->flush();

        $id = $position->getId();

        $this->_em->clear();

        $queryPosition = $this->_em->getRepository(self::POSITION)->find($id);
        $this->assertEquals($position, $queryPosition);
    }

    public function testPosition()
    {
        $position = new Position();
        $position->setPoint(new Point(42.6525793, -73.7562317));
        $this->_em->persist($position);
        $this->_em->flush();

        $id = $position->getId();

        $this->_em->clear();

        $queryPosition = $this->_em->getRepository(self::POSITION)->find($id);
        $this->assertEquals($position, $queryPosition);
    }
}
