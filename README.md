# doctrine2-mysql-spatial

Add MySQL spatial extension support to Doctrine2.

## Types
* Geometry
* Point
* LineString
* Polygon
* MultiPoint (todo)
* MultiLineString (todo)
* MultiPolygon (todo)
* GeometryCollection (todo)

## Functions
* GeomFromText
* GLength
* GeometryType
* X
* Y
* NumPoints
* Area
* NumInteriorRings
* LineString
* Point
* Polygon
* MBRContains
* MBRDisjoint
* MBREqual
* MBRIntersects
* MBROverlaps
* MBRTouches
* MBRWithin
* Contains
* Crosses
* Disjoint
* Equals
* Intersects
* Overlaps
* Touches
* Within
* EndPoint
* StartPoint
* AsText
* PointN (todo)
* Envelope (todo)
* ExteriorRing (todo)
* InteriorRingN (todo)
* NumInteriorRings (todo)
* ...more to come

## DQL AST Walker
There is a DQL AST walker that in conjunction with the AsText DQL function will return the appropriate Geometry type object from queries instead of strings.

        $query = $this->em->createQuery('SELECT AsText(Envelope(l.lineString)) MyLineStringEntity l');
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'CrEOF\ORM\Query\GeometryWalker');

Based on [jhartikainen/doctrine2-spatial](https://github.com/jhartikainen/doctrine2-spatial) and [Doctrine Docs](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html)
