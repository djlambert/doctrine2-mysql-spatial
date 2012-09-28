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

namespace CrEOF\DBAL\Types;

/**
 * Parse MySQL spatial WKT values
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class SpatialValueParser
{
    protected $stack;
    protected $current;
    protected $point;
    protected $marker;
    protected $position;
    protected $string;

    /**
     * @param string $string
     *
     * @return array
     */
    public function parse($string)
    {
        if (!$string) {
            return array();
        }

        $this->stack   = array();
        $this->point   = array();
        $this->current = array();
        $this->string  = substr($string, 1, -1);

        for ($this->position = 0; $this->position < strlen($this->string); $this->position++) {
            switch ($char = $this->string[$this->position]) {
                case '(':
                    $this->pushLevel();
                    break;
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                case '0':
                case '.':
                case '-':
                    if ($this->marker === null) {
                        $this->marker = $this->position;
                    }
                    break;
                case ' ':
                    $this->pushNumber();
                    break;
                case ',':
                    $this->pushPoint();
                    break;
                case ')':
                    $this->popLevel();
                    break;
            }
        }

        $this->pushPoint();

        return $this->current;
    }

    protected function pushNumber()
    {
        if ($this->marker !== null) {
            $this->point[] = substr($this->string, $this->marker, $this->position - $this->marker);;
            $this->marker  = null;
        }
    }

    protected function pushPoint()
    {
        if ($this->marker !== null) {
            $this->pushNumber();

            $this->current[] = $this->point;
            $this->point     = array();
        }
    }

    protected function pushLevel()
    {
        $this->stack[] = $this->current;
        $this->current = array();
    }

    protected function popLevel()
    {
        $this->pushPoint();

        $t             = array_pop($this->stack);
        $t[]           = $this->current;
        $this->current = $t;
    }
}
