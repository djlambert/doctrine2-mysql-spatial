<?php
namespace CrEOF\DBAL\Types;

use CrEOF\PHP\Types\Point;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for MySQL spatial POINT objects
 */
class PointType extends Type
{
    const POINT = 'point';

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
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array            $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform         The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'POINT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $data = unpack('x/x/x/x/corder/Ltype/dlat/dlon', $value);
        return new Point($data['lat'], $data['lon']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value) return;

        return pack('xxxxcLdd', '0', 1, $value->getLatitude(), $value->getLongitude());
    }
}
