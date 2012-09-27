<?php

namespace CrEOF\DBAL\Types;

use CrEOF\PHP\Types\Point;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for MySQL spatial POINT objects
 */
class PointType extends GeometryType
{
    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::POINT;
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return Point|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value) {
            list($latitude, $longitude) = sscanf($value, 'POINT(%f %f)');

            return new Point($latitude, $longitude);
        }
    }
}
