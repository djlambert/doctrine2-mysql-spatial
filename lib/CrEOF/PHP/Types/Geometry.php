<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

/**
 * Base geometry object
 */
abstract class Geometry
{
    protected function isValidPoint($point)
    {
        if (!($point instanceof Point)) {
            return false;
        }

        return true;
    }

    protected function isValidPointArray(array $array)
    {
        foreach ($array as $point) {
            if (!$this->isValidPoint($point)) {
                return false;
            };
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
