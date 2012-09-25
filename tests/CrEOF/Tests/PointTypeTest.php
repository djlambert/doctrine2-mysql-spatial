<?php

namespace CrEOF\Tests;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;

class PointTypeTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    private static $_setup = false;

    const POSITION = 'CrEOF\Tests\Position';

    protected function setUp() {
        parent::setUp();
        if (!static::$_setup) {
            $this->_schemaTool->createSchema(array(
                $this->_em->getClassMetadata(self::POSITION),
            ));
            static::$_setup = true;
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM Position');

        $this->_em->clear();
    }

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

/**
 * @Entity
 * @Table(options={"engine"="MyISAM"})
 */
class Position
{
    /**
     * @var int $id
     *
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @var Point $point
     *
     * @Column(type="point", nullable=true)
     */
    protected $point;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set point
     *
     * @param Point $point
     *
     * @return self
     */
    public function setPoint(Point $point)
    {
        $this->point = $point;
        return $this;
    }

    /**
     * Get point
     *
     * @return Point
     */
    public function getPoint()
    {
        return $this->point;
    }
}
