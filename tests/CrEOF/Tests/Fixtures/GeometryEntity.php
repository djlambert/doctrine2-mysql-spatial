<?php

namespace CrEOF\Tests\Fixtures;

use CrEOF\PHP\Types\Geometry;

/**
 * Geometry entity
 *
 * @Entity
 * @Table(options={"engine"="MyISAM"})
 */
class GeometryEntity
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
     * @var Geometry $geometry
     *
     * @Column(type="geometry", nullable=true)
     */
    protected $geometry;

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
     * Set geometry
     *
     * @param Geometry $geometry
     *
     * @return self
     */
    public function setGeometry(Geometry $geometry)
    {
        $this->geometry = $geometry;

        return $this;
    }

    /**
     * Get geometry
     *
     * @return Geometry
     */
    public function getGeometry()
    {
        return $this->geometry;
    }
}
