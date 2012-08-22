<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

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
     * @param mixed $value
     *
     * @return double
     * @throws InvalidValueException
     */
    protected function toDouble($value)
    {
        $regex = '/
^                                    # beginning of string
(?:
    (?:
        ([0-8]?[0-9])                # degrees 0-89
        (?::|°\s*)                   # colon or degree and optional spaces
        ([0-5]?[0-9])                # minutes 0-59
        (?::|(?:\'|\xe2\x80\xb2)\s*) # colon or minute or apostrophe and optional spaces
        ([0-5]?[0-9](?:\.\d+)?)      # seconds 0-59 and optional decimal
        (?:(?:"|\xe2\x80\xb3)\s*)?   # quote or double prime and optional spaces
        |
        (90)(?::|°\s*)(0?0)(?::|(?:\'|\xe2\x80\xb2)\s*)(0?0)(?:(?:"|\xe2\x80\xb3)\s*)?
    )
    ([NnSs])                         # N or S for latitude
    |
    (?:
        (0?[0-9]?[0-9]|1[0-7][0-9])  # degrees 0-89
        (?::|°\s*)                   # colon or degree and optional spaces
        ([0-5]?[0-9])                # minutes 0-59
        (?::|(?:\'|\xe2\x80\xb2)\s*) # colon or minute or apostrophe and optional spaces
        ([0-5]?[0-9](?:\.\d+)?)      # seconds 0-59 and optional decimal
        (?:(?:"|\xe2\x80\xb3)\s*)?   # quote or double prime and optional spaces
        |
        (180)(?::|°\s*)(0?0)(?::|(?:\'|\xe2\x80\xb2)\s*)(0?0)(?:(?:"|\xe2\x80\xb3)\s*)?
    )
    ([EeWw])                         # E or W for latitude
)
$                                    # end of string
/x';

        if (is_numeric($value)) {
            return (double) $value;
        } else {
            switch (1) {
                case preg_match_all($regex, $value, $matches, PREG_SET_ORDER):
                    break;
                default:
                    throw new InvalidValueException($value . ' is not a valid value.');
            }

            list(, $degrees, $minutes, $seconds, $direction) = array_values(array_filter($matches[0]));

            $value = $degrees + ((($minutes * 60) + $seconds) / 3600);

            switch (strtolower($direction)) {
                case 's':
                case 'w':
                    return (double) ($value * -1);
                    break;
                case 'n':
                case 'e':
                    return (double) $value;
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
