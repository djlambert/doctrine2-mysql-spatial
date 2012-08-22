<?php

namespace CrEOF\Tests;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\Point;

class PointTest extends \Doctrine\Tests\OrmFunctionalTestCase
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

    public function testGoodPoints()
    {
        // Test numeric parameters
        $point1 = new Point(42.6525793, -73.7562317);
        $this->assertEquals(42.6525793, $point1->getLatitude());
        $this->assertEquals(-73.7562317, $point1->getLongitude());

        // Test good string parameters
        $point2 = new Point('40:26:46N', '79:56:55W');
        $this->assertEquals(40.446111111111, $point2->getLatitude());
        $this->assertEquals(-79.948611111111, $point2->getLongitude());

        $point3 = new Point('40°26\'46"N', '79°56\'55"W');
        $this->assertEquals(40.446111111111, $point3->getLatitude());
        $this->assertEquals(-79.948611111111, $point3->getLongitude());

        $point4 = new Point('40° 26\' 46" N', '79° 56\' 55" W');
        $this->assertEquals(40.446111111111, $point4->getLatitude());
        $this->assertEquals(-79.948611111111, $point4->getLongitude());

        $point5 = new Point('40°26′46″N', '79°56′55″W');
        $this->assertEquals(40.446111111111, $point5->getLatitude());
        $this->assertEquals(-79.948611111111, $point5->getLongitude());

        $point6 = new Point('40° 26′ 46″ N', '79° 56′ 55″ W');
        $this->assertEquals(40.446111111111, $point6->getLatitude());
        $this->assertEquals(-79.948611111111, $point6->getLongitude());

        $point7 = new Point('40:26:46.543N', '79:56:55.832W');
        $this->assertEquals(40.446261944444, $point7->getLatitude());
        $this->assertEquals(-79.948842222222, $point7->getLongitude());

        $point7 = new Point('33:27:0N', '112:4:0W');
        $this->assertEquals(33.45, $point7->getLatitude());
        $this->assertEquals(-112.06666666667, $point7->getLongitude());
    }

    public function testBadPoint1()
    {
        // Test bad string parameters - invalid latitude direction
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46Q', '100:56:55W');
    }

    public function testBadPoint2()
    {
        // Test bad string parameters - latitude greater that 90
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('92:26:46N', '79:56:55W');
    }

    public function testBadPoint3()
    {
        // Test bad string parameters - latitude minutes greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:64:46N', '108:42:55W');
    }

    public function testBadPoint4()
    {
        // Test bad string parameters - latitude seconds greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:23:75N', '108:42:55W');
    }

    public function testBadPoint5()
    {
        // Test bad string parameters - invalid longitude direction
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '100:56:55P');
    }

    public function testBadPoint6()
    {
        // Test bad string parameters - longitude greater than 180
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '190:56:55W');
    }

    public function testBadPoint7()
    {
        // Test bad string parameters - longitude minutes greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '108:62:55W');
    }

    public function testBadPoint8()
    {
        // Test bad string parameters - longitude seconds greater than 59
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $point = new Point('84:26:46N', '108:53:94W');
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
