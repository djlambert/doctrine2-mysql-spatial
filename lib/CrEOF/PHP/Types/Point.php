<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

/**
 * Point object for MySQL spatial POINT type
 */
class Point
{
    /**
     * @var float $latitude
     */
    protected $latitude;

    /**
     * @var float $longitude
     */
    protected $longitude;

    /**
     * @param mixed $latitude
     * @param mixed $longitude
     */
    public function __construct($latitude = null, $longitude = null)
    {
        if ($latitude && $longitude) {
            $this->latitude = $this->toFloat($latitude);
            $this->longitude = $this->toFloat($longitude);
        }
    }

    /**
     * @param mixed $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $this->toFloat($latitude);

        return $this;
    }

    /**
     * @return float
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
        $this->longitude = $this->toFloat($longitude);

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $value
     *
     * @return float
     * @throws InvalidValueException
     */
    private function toFloat($value)
    {
        $regex = <<<EOD
/
^                                         # beginning of string
(?|
    (?|
        (?<degrees>[0-8]?[0-9])           # degrees 0-89
        (?::|°\s*)                        # colon or degree and optional spaces
        (?<minutes>[0-5]?[0-9])           # minutes 0-59
        (?::|(?:\'|\xe2\x80\xb2)\s*)      # colon or minute or apostrophe and optional spaces
        (?<seconds>[0-5]?[0-9](?:\.\d+)?) # seconds 0-59 and optional decimal
        (?:(?:"|\xe2\x80\xb3)\s*)?        # quote or double prime and optional spaces
        |
        (?<degrees>90)(?::|°\s*)(?<minutes>0?0)(?::|(?:\'|\xe2\x80\xb2)\s*)(?<seconds>0?0)(?:(?:"|\xe2\x80\xb3)\s*)?
    )
    (?<direction>[NnSs])                  # N or S for latitude
    |
    (?|
        (?<degrees>0?[0-9]?[0-9]|1[0-7][0-9]) # degrees 0-179
        (?::|°\s*)                            # colon or degree and optional spaces
        (?<minutes>[0-5]?[0-9])               # minutes 0-59
        (?::|(?:\'|\xe2\x80\xb2)\s*)          # colon or minute or apostrophe and optional spaces
        (?<seconds>[0-5]?[0-9](?:\.\d+)?)     # seconds 0-59 and optional decimal
        (?:(?:"|\xe2\x80\xb3)\s*)?            # quote or double prime and optional spaces
        |
        (?<degrees>180)(?::|°\s*)(?<minutes>0?0)(?::|(?:\'|\xe2\x80\xb2)\s*)(?<seconds>0?0)(?:(?:"|\xe2\x80\xb3)\s*)?
    )
    (?<direction>[EeWw])                      # E or W for latitude
)
$                                             # end of string
/x
EOD;

        if (is_numeric($value)) {
            return (float) $value;
        }

        switch (1) {
            case preg_match_all($regex, $value, $matches, PREG_SET_ORDER):
                break;
            default:
                throw new InvalidValueException($value . ' is not a valid value.');
        }

        $p = $matches[0];

        return ($p['degrees'] + ((($p['minutes'] * 60) + $p['seconds']) / 3600)) * (float) $this->getDirectionSign($p['direction']);
    }

    /**
     * @param string $direction
     *
     * @return int
     */
    private function getDirectionSign($direction)
    {
        switch (strtolower($direction)) {
            case 's':
            case 'w':
                return -1;
                break;
            case 'n':
            case 'e':
                return 1;
                break;
        }
    }
}
