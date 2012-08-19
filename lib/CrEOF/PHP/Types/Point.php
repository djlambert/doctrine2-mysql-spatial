<?php

namespace CrEOF\PHP\Types;

/**
 * Point object for MySQL spatial POINT type
 */
class Point
{
    private $latitude;
    private $longitude;

    /**
     * @param $latitude
     * @param $longitude
     */
    public function __construct($latitude, $longitude)
    {
            $this->latitude = (double)$latitude;
            $this->longitude = (double)$longitude;
    }

    /**
     * @param $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = (double)$latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = (double)$longitude;
    }

    /**
     * @return mixed
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
