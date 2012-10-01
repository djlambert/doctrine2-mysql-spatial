<?php
/**
 * Copyright (C) 2012 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;
use CrEOF\PHP\Types\Point;

/**
 * Polygon object for MySQL spatial POLYGON type
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
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
    public function getValue()
    {
        return $this->getPolygonArrayString($this->polygons);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::POLYGON;
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
