<?php

namespace CrEOF\PHP\Types;

/**
 * Point object for MySQL spatial POINT type
 */
class Point
{
    /**
     * @var double $latitude
     */
    protected $latitude;

    /**
     * @var double $longitude
     */
    protected $longitude;

    /**
     * @param mixed $position
     *
     * @return double
     * @throws \Exception
     */
    protected function toDouble($position)
    {
        if (strpos($position, ':') === false) {
            return (double) $position;
        } else {
            $found = preg_match_all('/^(?:(?:([0-8]?[0-9]):([0-5]?[0-9]):([0-5]?[0-9])|(90):(0?0):(0?0))([NnSs])|(?:(0?[0-9]?[0-9]|1[0-7][0-9]):([0-5]?[0-9]):([0-5]?[0-9])|(180):(0?0):(0?0))([EeWw]))$/', $position, $matches, PREG_SET_ORDER);

            if ($found != 1) {
                throw new \Exception();
            }
            list( , $degrees, $minutes, $seconds, $direction) = array_values(array_filter($matches[0]));

            $position = $degrees + ((($minutes * 60) + $seconds) / 3600);

            switch (strtolower($direction)) {
                case 's':
                case 'w':
                    return (double) ($position * -1);
                    break;
                case 'n':
                case 'e':
                    return (double) $position;
                    break;
            }
        }
    }

    /**
     * @param mixed $latitude
     * @param mixed $longitude
     */
    public function __construct($latitude = null, $longitude = null)
    {
        if ($latitude && $longitude) {
            $this->latitude = $this->toDouble($latitude);
            $this->longitude = $this->toDouble($longitude);
        }
    }

    /**
     * @param mixed $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $this->toDouble($latitude);
        return $this;
    }

    /**
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $this->toDouble($longitude);
        return $this;
    }

    /**
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        //Output from this is used with POINT_STR in DQL so must be in specific format
        return sprintf('POINT(%f %f)', $this->latitude, $this->longitude);
    }
}
