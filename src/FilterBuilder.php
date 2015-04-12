<?php

namespace Cake\ElasticSearch;

use Elastica\Filter;
use Elastica\Filter\AbstractFilter;
use Elastica\Query\AbstractQuery;

class FilterBuilder
{

    /**
    * Returns a Range filter object setup to filter documents having the field between
    * a `from` and a `to` value
    *
    * @param string $field The field to filter by.
    * @param mixed $from The lower bound value.
    * @param mixed $to The upper bound value.
    * @return Elastica\Filter\Range
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-filter.html
    */
    public function between($field, $from, $to)
    {
        return $this->range($field, [
            'gte' => $from,
            'lte' => $to
        ]);
    }

    /**
    * Returns a bool filter that can be chained with the `addMust()`, `addShould()`
    * and `addMustNot()` methods.
    *
    * @return Elastica\Filter\Bool
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-filter.html
    */
    public function bool()
    {
        return new Filter\Bool();
    }

    /**
    * Returns an Exists filter object setup to filter documents having a property present
    * or not set to null.
    *
    * @param string $field The field to check for existance.
    * @return Elastica\Filter\Exists
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-filter.html
    */
    public function exists($field)
    {
        return new Filter\Exists($field);
    }

    /**
    * Returns a GeoBoundingBox filter object setup to filter documents having a property
    * bound by two coordinates.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoBoundingBox('location', [40.73, -74.1], [40.01, -71.12]);
    *
    *    $filter = $builder->geoBoundingBox(
    *        'location',
    *        ['lat => 40.73, 'lon' => -74.1],
    *        ['lat => 40.01, 'lon' => -71.12]
    *    );
    *
    *    $filter = $builder->geoBoundingBox('location', 'dr5r9ydj2y73', 'drj7teegpus6');
    * }}}
    *
    * @param string $field The field to compare.
    * @param array|string $topLeft The top left coordinate.
    * @param array|string $bottomRight The bottom right coordinate.
    * @return Elastica\Filter\GeoBoundingBox
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-bounding-box-filter.html
    */
    public function geoBoundingBox($field, $topLeft, $bottomRight)
    {
        return new Filter\GeoBoundingBox($field, [$topLeft, $bottomRight]);
    }

    /**
    * Returns an GeoDistance filter object setup to filter documents having a property
    * in the radius distance of a coordinate.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoDistance('location', [40.73, -74.1], '10km');
    *
    *    $filter = $builder->geoBoundingBox('location', 'dr5r9ydj2y73', '5km');
    * }}}
    *
    * @param string $field The field to compare.
    * @param array|string $location The coordinate from which to compare.
    * @param string $distance The distance radius.
    * @return Elastica\Filter\GeoDistance
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-range-filter.html
    */
    public function geoDistance($field, $location, $distance)
    {
        return new Filter\GeoDistance($field, $location, $distance);
    }

    /**
    * Returns an GeoDistanceRange filter object setup to filter documents having a property
    * in between two distance radius from a location coordinate.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoDistanceRange('location', [40.73, -74.1], '10km', '20km');
    *
    *    $filter = $builder->geoDistanceRange('location', 'dr5r9ydj2y73', '5km', '10km');
    * }}}
    *
    * @param string $field The field to compare.
    * @param array|string $location The coordinate from which to compare.
    * @param string $from The initial distance radius.
    * @param string $top The ending distance radius.
    * @return Elastica\Filter\GeoDistanceRange
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-filter.html
    */
    public function geoDistanceRange($field, $location, $from, $to)
    {
        return new Filter\GeoDistanceRange($field, $location, [
            'gte' => $from,
            'lte' => $to
        ]);
    }

    /**
    * Returns an GeoPolygon filter object setup to filter documents having a property
    * enclosed in the polygon induced by the passed geo points.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoPolygon('location', [
    *        ['lat' => 40, 'lon' => -70],
    *        ['lat' => 30, 'lon' => -80],
    *        ['lat' => 20, 'lon' => -90],
    *    ]);
    *
    *    $filter = $builder->geoPolygon('location', [
    *        'drn5x1g8cu2y',
    *        ['lat' => 30, 'lon' => -80],
    *        '20, -90',
    *    ]);
    * }}}
    *
    * @param string $field The field to compare.
    * @param array $geoPoints List of geo points that form the polygon
    * @return Elastica\Filter\GeoPolygon
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-polygon-filter.html
    */
    public function geoPolygon($field, array $geoPoints)
    {
        return new Filter\GeoPolygon($field, $geoPoints);
    }

    /**
    * Returns an GeoShapeProvided filter object setup to filter documents having a property
    * enclosed in the specified geometrical shape type.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoShape('location', [[13.0, 53.0], [14.0, 52.0]], 'envelope');
    *
    *    $filter = $builder->geoShape('location', [
    *        [[-77.03653, 38.897676], [-77.009051, 38.889939]],
    *        'linestring'
    *    ]);
    * }}}
    *
    * You can read about the supported shapes and how they are created here:
    * http://www.elastic.co/guide/en/elasticsearch/reference/1.x/mapping-geo-shape-type.html
    *
    * @param string $field The field to compare.
    * @param array $geoPoints List of geo points that form the shape.
    * @param string $type The shape type to use (envelope, linestring, polygon, multipolygon...)
    * @return Elastica\Filter\GeoShapeProvided
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-filter.html
    */
    public function geoShape($field, array $geoPoints, $type = 'envelope')
    {
        return new Filter\GeoShapeProvided($field, $geoPoints, $type);
    }

    /**
    * Returns an GeoShapePreIndex filter object setup to filter documents having a property
    * enclosed in the specified geometrical shape type.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoShapeIndex('location', 'DEU', 'countries', 'shapes', 'location');
    * }}}
    *
    * @param string $field The field to compare.
    * @param string $id The ID of the document containing the pre-indexed shape.
    * @param string $type Index type where the pre-indexed shape is.
    * @param string $index Name of the index where the pre-indexed shape is.
    * @param string The field specified as path containing the pre-indexed shape.
    * @return Elastica\Filter\GeoShapePreIndex
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-filter.html
    */
    public function geoShapeIndex($field, $id, $type, $index = 'shapes', $path = 'shape')
    {
        return new Filter\GeoShapePreIndexed($field, $id, $type, $index, $path);
    }

    /**
    * Returns an GeohashCell filter object setup to filter documents having a property
    * enclosed inside the specified geohash in teh give precision.
    *
    * ### Example:
    *
    * {{{
    *    $filter = $builder->geoHashCell('location', [40, -70], 3);
    * }}}
    *
    * @param string $field The field to compare.
    * @param string|array $location Location as coordinates array or geohash string.
    * @param int|string $precision Length of geohash prefix or distance (3, or "50m")
    * @param string $index Name of the index where the pre-indexed shape is.
    * @param bool $neighbors If true, filters cells next to the given cell.
    * @return Elastica\Filter\GeohashCell
    * @see http://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geohash-cell-filter.html
    */
    public function geoHashCell($field, $location, $precision = -1, $neighbors = false)
    {
        return new Filter\GeohashCell($field, $location, $precision, $neighbors);
    }

    public function gt($field, $value)
    {
        return $this->range($field, ['gt' => $value]);
    }

    public function gte($field, $value)
    {
        return $this->range($field, ['gte' => $value]);
    }

    public function hasChild($query, $type = null)
    {
        return new Filter\HasChild($query, $type);
    }

    public function hasParent()
    {
        return new Filter\HasParent($query, $type);
    }

    public function ids(array $ids = [], $type = null)
    {
        return new Filter\Ids($type, $ids);
    }

    public function indices(AbstractFilter $filter, array $indices)
    {
        return new Filter\Indices($filter, $indices);
    }

    public function limit($limit)
    {
        return new Filter\Limit((int)$limit);
    }

    public function matchAll()
    {
        return new Filter\MatchAll();
    }

    public function lt($field, $value)
    {
        return $this->range($field, ['lt' => $value]);
    }

    public function lte($field, $value)
    {
        return $this->range($field, ['lte' => $value]);
    }

    public function missing($field = '')
    {
        return new Filter\Missing($field);
    }

    public function nested($path, $filter)
    {
        $nested = new Filter\Nested();
        $nested->setPath($path);

        if ($filter instanceof $filter) {
            $nested->setFilter($filter);
        }

        if ($filter instanceof AbstractQuery) {
            $nested->setQuery($filter);
        }
        return $nested;
    }

    public function not($filter)
    {
        return new Filter\BoolNot($filter);
    }

    public function prefix($field, $prefix)
    {
        return new Filter\Prefix($field, $prefix);
    }

    public function query($query)
    {
        return new Filter\Query($query);
    }

    public function range($field, array $args)
    {
        return new Filter\Range($field, $args);
    }

    public function regexp($field, $regexp, array $options = [])
    {
        return new Filter\Regexp($field, $args);
    }

    public function script($script)
    {
        return new Filter\Script($script);
    }

    public function term($field, $value)
    {
        return new Filter\Term([$field => $value]);
    }

    public function terms($field, $values)
    {
        return new Filter\Terms($field, $values);
    }

    public function type($type)
    {
        return new Filter\Type($type);
    }

    public function and_()
    {
        $filters = func_get_args();
        $bool = $this->bool();

        foreach ($filters as $k => $filter) {
            if ($filter instanceof Filter\Bool) {
                $bool = $filter;
                unset($filters[$k]);
                break;
            }
        }

        foreach ($filters as $filter) {
            $bool->addMust($filter);
        }

        return $bool;
    }

    public function or_()
    {
        $filters = func_get_args();
        $or = new Filter\BoolOr();

        foreach ($filters as $filter) {
            $or->addFilter($filter);
        }

        return $or;
    }

    public function __call($method, $args)
    {
        if (in_array($method, ['and', 'or'])) {
            return call_user_func_array([$this, $method . '_'], $args);
        }
        throw new \BadMethodCallException('Cannot build filter ' . $method);
    }

    public function parse($conditions)
    {
        if ($conditions instanceof AbstractFilter) {
            return $conditions;
        }

        $result = [];
        foreach ($conditions as $k => $c) {
            $numericKey = is_numeric($k);
            $operator = strtolower($k);

            if ($numericKey) {
                $c = $this->parse($c);
                if (is_array($c)) {
                    $c = $this->__call('and', $c);
                }
                $result[] = $c;
                continue;
            }

            if ($operator === 'and') {
                $result[] = $this->__call('and', $this->parse($c));
                continue;
            }

            if ($operator === 'or') {
                $result[] = $this->__call('or', $this->parse($c));
                continue;
            }

            if ($operator === 'not') {
                $result[] = $this->not($this->parse($c));
                continue;
            }

            if ($c instanceof AbstractFilter) {
                $result[] = $c;
                continue;
            }

            if (!$numericKey) {
                $result[] = $this->_parseFilter($k, $c);
            }
        }

        return $result;
    }

    protected function _parseFilter($field, $value)
    {
        $operator = '=';
        $parts = explode(' ', trim($field), 2);

        if (count($parts) > 1) {
            list($field, $operator) = $parts;
        }

        $operator = strtolower(trim($operator));

        if ($operator === '>') {
            return $this->gt($field, $value);
        }

        if ($operator === '>=') {
            return $this->gte($field, $value);
        }

        if ($operator === '<') {
            return $this->lt($field, $value);
        }

        if ($operator === '<=') {
            return $this->lte($field, $value);
        }

        if (in_array($operator, ['in', 'not in'])) {
            $value = (array)$value;
        }

        if ($operator === 'in') {
            return $this->terms($field, $value);
        }

        if ($operator === 'not in') {
            return $this->not($this->terms($field, $value));
        }

        if ($operator === 'is' && $value === null) {
            return $this->missing($field);
        }

        if ($operator === 'is not' && $value === null) {
            return $this->not($this->missing($field));
        }

        if ($operator === 'is' && $value !== null) {
            return $this->term($field, $value);
        }

        if ($operator === 'is not' && $value !== null) {
            return $this->not($this->term($field, $value));
        }

        if ($operator === '!=') {
            return $this->not($this->term($field, $value));
        }

        return $this->term($field, $value);
    }
}
