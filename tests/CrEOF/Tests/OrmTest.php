<?php

namespace CrEOF\Tests;

use Doctrine\ORM\Query;

abstract class OrmTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    protected static $_setup = false;

    const GEOMETRY_ENTITY   = 'CrEOF\Tests\Fixtures\GeometryEntity';
    const POINT_ENTITY      = 'CrEOF\Tests\Fixtures\PointEntity';
    const LINESTRING_ENTITY = 'CrEOF\Tests\Fixtures\LineStringEntity';

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
                ));
            static::$_setup = true;
        }

        $this->_em->getConfiguration()->addCustomNumericFunction('glength', 'CrEOF\DBAL\Query\AST\Functions\GLength');
        $this->_em->getConfiguration()->addCustomStringFunction('geomfromtext', 'CrEOF\DBAL\Query\AST\Functions\GeomFromText');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM GeometryEntity');
        $conn->executeUpdate('DELETE FROM PointEntity');
        $conn->executeUpdate('DELETE FROM LineStringEntity');

        $this->_em->clear();
    }
}
