<?php

namespace CrEOF\DBAL\Types;

use CrEOF\PHP\Types\LineString;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for MySQL spatial POINT objects
 */
class LineStringType extends GeometryType
{
    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::LINE_STRING;
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return LineString|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value) {
            return new LineString($this->convertStringToPoints($value));
        }
    }
}
