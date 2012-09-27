<?php
namespace CrEOF\DBAL\Types;

use CrEOF\DBAL\Types\SpatialValueParser;
use CrEOF\PHP\Types\Geometry;
use CrEOF\PHP\Types\Point;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for MySQL spatial POINT objects
 */
class GeometryType extends Type
{
    const GEOMETRY    = 'geometry';
    const POINT       = 'point';
    const LINE_STRING = 'linestring';

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
