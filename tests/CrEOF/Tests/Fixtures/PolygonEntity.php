<?php

namespace CrEOF\Tests\Fixtures;

use CrEOF\PHP\Types\Polygon;

/**
 * Polygon entity
 *
 * @Entity
 * @Table(options={"engine"="MyISAM"})
 */
class PolygonEntity
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
     * @var Polygon $polygon
     *
     * @Column(type="polygon", nullable=true)
     */
    protected $polygon;

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
     * Set polygon
     *
     * @param Polygon $polygon
     *
     * @return self
     */
    public function setPolygon(Polygon $polygon)
    {
        $this->polygon = $polygon;

        return $this;
    }

    /**
     * Get polygon
     *
     * @return Polygon
     */
    public function getPolygon()
    {
        return $this->polygon;
    }
}
