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

namespace CrEOF\DBAL\Types;

use CrEOF\DBAL\Types\SpatialValueParser;
use CrEOF\PHP\Types\Geometry;
use CrEOF\PHP\Types\Point;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for MySQL spatial GEOMETRY type
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GeometryType extends Type
{
    const GEOMETRY    = 'geometry';
    const POINT       = 'point';
    const LINESTRING  = 'linestring';
    const POLYGON     = 'polygon';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::GEOMETRY;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array            $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform         The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return strtoupper($this->getName());
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return Geometry|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value) {
            $type = strtolower(substr($value, 0, strpos($value, '(')));
            $type = self::getType($type);

            // Refactor?
            return $type->convertToPHPValue($value, $platform);
        }
    }

    /**
     * @param Point            $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Geometry) {
            return (string) $value;
        }
    }

    /**
     * @return bool
     */
    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * @param string           $sqlExpr
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return sprintf('AsText(%s)', $sqlExpr);
    }

    /**
     * @param string           $sqlExpr
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return sprintf('GeomFromText(%s)', $sqlExpr);
    }

    protected function convertStringToPoints($string)
    {
        $coordinateArrays = $this->convertStringToCoordinateArrays($string);
        $pointArrays      = array();

        foreach ($coordinateArrays as $val) {
            $pointArrays[] = $this->convertArrayToPoints($val);
        }

        return $pointArrays;
    }

    private function convertArrayToPoints(array $array)
    {
        if (!is_array($array[0])) {
            return new Point($array[0], $array[1]);
        }

        foreach ($array as &$val) {
            $val = $this->convertArrayToPoints($val);
        }

        return $array;
    }

    private function convertStringToCoordinateArrays($string)
    {
        $string = substr($string, strpos($string, '('));
        $parser = new SpatialValueParser();

        return $parser->parse($string);
    }

}
