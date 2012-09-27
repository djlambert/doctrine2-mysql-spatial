<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;
use CrEOF\PHP\Types\Point;

/**
 * Polygon object for MySQL spatial POLYGON type
 */
class Polygon extends Geometry
{
    /**
     * @var int $pointsType
     */
    protected $pointsType;

    /**
     * @param array $points
     *
     * @throws InvalidValueException
     */
    public function __construct(array $points)
    {
        $this->setPoints($points);
    }

    /**
     * @param Point|array $point
     *
     * @return self
     * @throws InvalidValueException
     */
    public function addPoint($point)
    {
        if ($this->pointsType && $this->pointsType != gettype($point)) {
            throw InvalidValueException::mixedValues();
        }
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
     * @param array $points
     *
     * @return self
     */
    public function setPoints(array $points)
    {
        $this->validatePolygonArray($points);

        $this->points = $points;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'POLYGON(' . $this->getPolygonArrayString($this->points) . ')';
    }

    private function getPolygonArrayString(array $points)
    {
        if ($this->pointsType == self::POINT_VALUES) {
            return $this->getPointArrayString($points);
        }

        $string = null;

        foreach ($points as $array) {
            $string .= ($string ? ', ': null) . '(' . $this->getPointArrayString($array) . ')';
        }

        return $string;
    }

    private function validatePolygonArray(array $array)
    {
        foreach ($array as $value) {
            switch (gettype($value)) {
                case 'array':
                    $this->pointsType = $this->validatePointArray($value, $this->pointsType);
                    break;
                case 'object':
                    $this->pointsType = $this->validatePoint($value, $this->pointsType);
                    break;
                default:
                    throw new InvalidValueException('Value not of type Point or array!!');
                    break;
            }
        }

        return true;
    }
}
