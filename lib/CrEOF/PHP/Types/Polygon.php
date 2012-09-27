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
     * @var array $polygons
     */
    protected $polygons = array();

    /**
     * @param array $polygons
     */
    public function __construct(array $polygons)
    {
        $this->setPolygons($polygons);
    }

    /**
     * @param array $polygon
     *
     * @return self
     */
    public function addPolygon(array $polygon)
    {
        $this->polygons[] = $polygon;

        return $this;
    }

    /**
     * @return array
     */
    public function getPolygons()
    {
        return $this->polygons;
    }

    /**
     * @param array $polygons
     *
     * @return self
     * @throws InvalidValueException
     */
    public function setPolygons(array $polygons)
    {
        if (!$this->isValidPolygonArray($polygons)) {
            throw InvalidValueException::valueNotArray();
        }

        $this->polygons = $polygons;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'POLYGON(' . $this->getPolygonArrayString($this->polygons) . ')';
    }

    private function getPolygonArrayString(array $points)
    {
        $string = null;

        foreach ($points as $array) {
            $string .= ($string ? ', ': null) . '(' . $this->getPointArrayString($array) . ')';
        }

        return $string;
    }

    private function isValidPolygonArray(array $array)
    {
        foreach ($array as $value) {
            if (!is_array($value)) {
                return false;
            }

            if (!$this->isValidPointArray($value)) {
                return false;
            }
        }

        return true;
    }
}
