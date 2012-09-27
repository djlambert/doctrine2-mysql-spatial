<?php

namespace CrEOF\Tests\Fixtures;

use CrEOF\PHP\Types\LineString;

/**
 * LineString entity
 *
 * @Entity
 * @Table(options={"engine"="MyISAM"})
 */
class LineStringEntity
{
    /**
     * @var int $id
     *
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @var LineString $lineString
     *
     * @Column(type="linestring", nullable=true)
     */
    protected $lineString;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lineString
     *
     * @param LineString $lineString
     *
     * @return self
     */
    public function setLineString(LineString $lineString)
    {
        $this->lineString = $lineString;

        return $this;
    }

    /**
     * Get lineString
     *
     * @return LineString
     */
    public function getLineString()
    {
        return $this->lineString;
    }
}
