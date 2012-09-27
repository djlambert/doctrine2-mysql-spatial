<?php
namespace CrEOF\Exception;

use Exception;

class InvalidValueException extends Exception
{
    static public function valueNotPoint()
    {
        return new self('Value not of type Point!');
    }
}
