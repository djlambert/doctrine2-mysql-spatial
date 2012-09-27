<?php
namespace CrEOF\Exception;

use Exception;

class InvalidValueException extends Exception
{
    static public function valueNotPoint()
    {
        return new self('Value not of type Point!');
    }

    static public function mixedValues()
    {
        return new self('Cannot mix array and Point values!');
    }
}
