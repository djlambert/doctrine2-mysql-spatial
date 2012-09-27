<?php

namespace CrEOF\DBAL\Types;

/**
 * Parse MySQL spatial WKT values
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
