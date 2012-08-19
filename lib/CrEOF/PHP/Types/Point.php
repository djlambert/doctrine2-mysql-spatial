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
    private $latitude;

    /**
     * @var double $longitude
     */
    private $longitude;

    /**
     * @param mixed $latitude
     * @param mixed $longitude
     */
    public function __construct($latitude, $longitude)
    {
            $this->latitude = (double)$latitude;
            $this->longitude = (double)$longitude;
    }

    /**
     * @param mixed $latitude
     *
     */
    public function setLatitude($latitude)
    {
        $this->latitude = (double)$latitude;
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
     */
    public function setLongitude($longitude)
    {
        $this->longitude = (double)$longitude;
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
