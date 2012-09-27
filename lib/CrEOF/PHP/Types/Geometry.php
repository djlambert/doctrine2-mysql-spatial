<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

/**
 * Base geometry object
 */
abstract class Geometry
{
    const ARRAY_VALUES = 1;
    const POINT_VALUES = 2;

    /**
     * @var array $points
     */
    protected $points = array();

    protected function validatePoint($point, $type = null)
    {
        if ($type && $type != self::POINT_VALUES) {
            throw InvalidValueException::mixedValues();
        }

        if (!($point instanceof Point)) {
            throw InvalidValueException::valueNotPoint();
        }

        return self::POINT_VALUES;
    }

    protected function validatePointArray(array $array, $type = null)
    {
        if ($type && $type != self::ARRAY_VALUES) {
            throw InvalidValueException::mixedValues();
        }

        foreach ($array as $point) {
            $this->validatePoint($point);
        }

        return self::ARRAY_VALUES;
    }

    protected function getPointArrayString(array $points)
    {
        $string = null;

        foreach ($points as $point) {
            $string .= ($string ? ', ': null) . $point->getCoordinates();
        }

        return $string;
    }
}
