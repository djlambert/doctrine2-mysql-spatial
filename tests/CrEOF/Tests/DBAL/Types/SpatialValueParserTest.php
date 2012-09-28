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

namespace CrEOF\Tests\DBAL\Types;

use CrEOF\DBAL\Types\SpatialValueParser;

/**
 * SpatialValueParser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class SpatialValueParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSinglePoint()
    {
        $parser = new SpatialValueParser();
        $value  = array(
            array(5.45, -23.5)
        );
        $string = '(5.45 -23.5)';
        $result = $parser->parse($string);

        $this->assertEquals($value, $result);
    }

    public function testMultiPoint()
    {
        $parser = new SpatialValueParser();
        $value  = array(
            array(5.45, -23.5),
            array(-4.2, 99),
            array(23, 57.2345)
        );
        $string = '(5.45 -23.5, -4.2 99, 23 57.2345)';
        $result = $parser->parse($string);

        $this->assertEquals($value, $result);
    }

    public function testNestedMultiPoint()
    {
        $parser = new SpatialValueParser();
        $value  = array(
            array(
                array(5.45, -23.5),
                array(-4.2, 99),
                array(23, 57.2345)
                ),
            array(
                array(5.45, -23.5),
                array(-4.2, 99),
                array(23, 57.2345)
            )
        );
        $string = '((5.45 -23.5, -4.2 99, 23 57.2345), (5.45 -23.5, -4.2 99, 23 57.2345))';
        $result = $parser->parse($string);

        $this->assertEquals($value, $result);
    }
}
