<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

/**
 * Base geometry object
 */
abstract class Geometry
{
    const ARRAY_VALUE = 1;
    const POINT_VALUE = 2;

    /**
     * @var array $points
     */
    protected $points = array();

    protected function validatePoint($point, $type = null)
    {
        if ($type && $type != self::POINT_VALUE) {
            throw InvalidValueException::mixedValues();
        }

        if (!($point instanceof Point)) {
            throw InvalidValueException::valueNotPoint();
        }

        return self::POINT_VALUE;
    }

    protected function validatePointArray(array $array, $type = null)
    {
        if ($type && $type != self::ARRAY_VALUE) {
            throw InvalidValueException::mixedValues();
        }

        foreach ($array as $point) {
            $this->validatePoint($point);
        }

        return self::ARRAY_VALUE;
    }
}
