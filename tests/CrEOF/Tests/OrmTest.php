<?php

namespace CrEOF\Tests;

use Doctrine\ORM\Query;

abstract class OrmTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    protected static $_setup = false;

    const GEOMETRY_ENTITY   = 'CrEOF\Tests\Fixtures\GeometryEntity';
    const POINT_ENTITY      = 'CrEOF\Tests\Fixtures\PointEntity';
    const LINESTRING_ENTITY = 'CrEOF\Tests\Fixtures\LineStringEntity';
    const POLYGON_ENTITY    = 'CrEOF\Tests\Fixtures\PolygonEntity';

    protected function setUp() {
        parent::setUp();

        if (!static::$_setup) {
            \Doctrine\DBAL\Types\Type::addType('geometry', '\CrEOF\DBAL\Types\GeometryType');
            \Doctrine\DBAL\Types\Type::addType('point', '\CrEOF\DBAL\Types\PointType');
            \Doctrine\DBAL\Types\Type::addType('linestring', '\CrEOF\DBAL\Types\LineStringType');
            \Doctrine\DBAL\Types\Type::addType('polygon', '\CrEOF\DBAL\Types\PolygonType');

            $this->_schemaTool->createSchema(
                array(
                     $this->_em->getClassMetadata(self::GEOMETRY_ENTITY),
                     $this->_em->getClassMetadata(self::POINT_ENTITY),
                     $this->_em->getClassMetadata(self::LINESTRING_ENTITY),
                     $this->_em->getClassMetadata(self::POLYGON_ENTITY)
                ));
            static::$_setup = true;
        }

        $this->_em->getConfiguration()->addCustomNumericFunction('glength', 'CrEOF\ORM\Query\AST\Functions\GLength');
        $this->_em->getConfiguration()->addCustomNumericFunction('x', 'CrEOF\ORM\Query\AST\Functions\X');
        $this->_em->getConfiguration()->addCustomNumericFunction('y', 'CrEOF\ORM\Query\AST\Functions\Y');
        $this->_em->getConfiguration()->addCustomNumericFunction('numpoints', 'CrEOF\ORM\Query\AST\Functions\NumPoints');
        $this->_em->getConfiguration()->addCustomNumericFunction('area', 'CrEOF\ORM\Query\AST\Functions\Area');
        $this->_em->getConfiguration()->addCustomNumericFunction('numinteriorrings', 'CrEOF\ORM\Query\AST\Functions\NumInteriorRings');
        $this->_em->getConfiguration()->addCustomStringFunction('geomfromtext', 'CrEOF\ORM\Query\AST\Functions\GeomFromText');
        $this->_em->getConfiguration()->addCustomStringFunction('geometrytype', 'CrEOF\ORM\Query\AST\Functions\GeometryType');
        $this->_em->getConfiguration()->addCustomStringFunction('linestring', 'CrEOF\ORM\Query\AST\Functions\LineString');
        $this->_em->getConfiguration()->addCustomStringFunction('point', 'CrEOF\ORM\Query\AST\Functions\Point');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM GeometryEntity');
        $conn->executeUpdate('DELETE FROM PointEntity');
        $conn->executeUpdate('DELETE FROM LineStringEntity');
        $conn->executeUpdate('DELETE FROM PolygonEntity');

        $this->_em->clear();
    }
}
