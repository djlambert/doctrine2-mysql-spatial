<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

/**
 * Base geometry object
 */
abstract class Geometry
{
    protected function validatePoint($point)
    {
        if (!($point instanceof Point)) {
            throw InvalidValueException::valueNotPoint();
        }

        return true;
    }

    protected function validatePointArray(array $array)
    {
        foreach ($array as $point) {
            $this->validatePoint($point);
        }

        return true;
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
