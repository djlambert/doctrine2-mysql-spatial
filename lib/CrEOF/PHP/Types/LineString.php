<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;
use CrEOF\PHP\Types\Point;

/**
 * LineString object for MySQL spatial LINESTRING type
 */
class LineString extends Geometry
{
    /**
     * @var array $points
     */
    protected $points = array();

    /**
     * @param array $points
     *
     * @throws InvalidValueException
     */
    public function __construct(array $points)
    {
        foreach ($points as $point) {
            if (!($point instanceof Point)) {
                throw InvalidValueException::valueNotPoint();
            }
            $this->addPoint($point);
        }
    }

    /**
     * @param Point $point
     *
     * @return self
     */
    public function addPoint(Point $point)
    {
        $this->points[] = $point;

        return $this;
    }

    /**
     * @return array
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $points = null;

        foreach ($this->points as $point) {
            $points .= ($points ? ', ': null) . $point->getCoordinates();
        }

        return "LINESTRING($points)";
    }
}
