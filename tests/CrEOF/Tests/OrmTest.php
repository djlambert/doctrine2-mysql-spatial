<?php
/**
 * Copyright (C) 2012 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Tests;

use Doctrine\ORM\Query;

/**
 * Abstract ORM test class
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
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
        $this->_em->getConfiguration()->addCustomNumericFunction('mbrcontains', 'CrEOF\ORM\Query\AST\Functions\MBRContains');
        $this->_em->getConfiguration()->addCustomNumericFunction('mbrdisjoint', 'CrEOF\ORM\Query\AST\Functions\MBRDisjoint');
        $this->_em->getConfiguration()->addCustomNumericFunction('mbrequal', 'CrEOF\ORM\Query\AST\Functions\MBREqual');
        $this->_em->getConfiguration()->addCustomNumericFunction('mbrintersects', 'CrEOF\ORM\Query\AST\Functions\MBRIntersects');
        $this->_em->getConfiguration()->addCustomNumericFunction('mbroverlaps', 'CrEOF\ORM\Query\AST\Functions\MBROverlaps');
        $this->_em->getConfiguration()->addCustomNumericFunction('mbrtouches', 'CrEOF\ORM\Query\AST\Functions\MBRTouches');
        $this->_em->getConfiguration()->addCustomNumericFunction('mbrwithin', 'CrEOF\ORM\Query\AST\Functions\MBRWithin');
        $this->_em->getConfiguration()->addCustomNumericFunction('contains', 'CrEOF\ORM\Query\AST\Functions\Contains');
        $this->_em->getConfiguration()->addCustomNumericFunction('crosses', 'CrEOF\ORM\Query\AST\Functions\Crosses');
        $this->_em->getConfiguration()->addCustomNumericFunction('disjoint', 'CrEOF\ORM\Query\AST\Functions\Disjoint');
        $this->_em->getConfiguration()->addCustomNumericFunction('equals', 'CrEOF\ORM\Query\AST\Functions\Equals');
        $this->_em->getConfiguration()->addCustomNumericFunction('intersects', 'CrEOF\ORM\Query\AST\Functions\Intersects');
        $this->_em->getConfiguration()->addCustomNumericFunction('overlaps', 'CrEOF\ORM\Query\AST\Functions\Overlaps');
        $this->_em->getConfiguration()->addCustomNumericFunction('touches', 'CrEOF\ORM\Query\AST\Functions\Touches');
        $this->_em->getConfiguration()->addCustomNumericFunction('within', 'CrEOF\ORM\Query\AST\Functions\Within');
        $this->_em->getConfiguration()->addCustomStringFunction('geomfromtext', 'CrEOF\ORM\Query\AST\Functions\GeomFromText');
        $this->_em->getConfiguration()->addCustomStringFunction('geometrytype', 'CrEOF\ORM\Query\AST\Functions\GeometryType');
        $this->_em->getConfiguration()->addCustomStringFunction('linestring', 'CrEOF\ORM\Query\AST\Functions\LineString');
        $this->_em->getConfiguration()->addCustomStringFunction('point', 'CrEOF\ORM\Query\AST\Functions\Point');
        $this->_em->getConfiguration()->addCustomStringFunction('polygon', 'CrEOF\ORM\Query\AST\Functions\Polygon');
        $this->_em->getConfiguration()->addCustomStringFunction('endpoint', 'CrEOF\ORM\Query\AST\Functions\EndPoint');
        $this->_em->getConfiguration()->addCustomStringFunction('startpoint', 'CrEOF\ORM\Query\AST\Functions\StartPoint');
        $this->_em->getConfiguration()->addCustomStringFunction('astext', 'CrEOF\ORM\Query\AST\Functions\AsText');
        $this->_em->getConfiguration()->addCustomStringFunction('envelope', 'CrEOF\ORM\Query\AST\Functions\Envelope');
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
