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

/**
 * Abstract geometry object for MySQL spatial GEOMETRY type
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
abstract class Geometry
{
    const POINT      = 'Point';
    const LINESTRING = 'LineString';
    const POLYGON    = 'Polygon';

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getValue();

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s(%s)', $this->getType(), $this->getValue());
    }

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
            $string .= ($string ? ', ': null) . $point->getValue();
        }

        return $string;
    }
}
