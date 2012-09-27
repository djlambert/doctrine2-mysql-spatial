<?php

namespace CrEOF\DBAL\Types;

use CrEOF\PHP\Types\Polygon;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for MySQL spatial POLYGON objects
 */
class PolygonType extends GeometryType
{
    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::POLYGON;
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return Polygon|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value) {
            return new Polygon($this->convertStringToPoints($value));
        }
    }
}
