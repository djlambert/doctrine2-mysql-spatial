<?php

namespace CrEOF\Tests\Fixtures;

use CrEOF\PHP\Types\Point;

/**
 * Position entity
 *
 * @Entity
 * @Table(options={"engine"="MyISAM"})
 */
class PointEntity
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
     * @var Point $point
     *
     * @Column(type="point", nullable=true)
     */
    protected $point;

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
     * Set point
     *
     * @param Point $point
     *
     * @return self
     */
    public function setPoint(Point $point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return Point
     */
    public function getPoint()
    {
        return $this->point;
    }
}
