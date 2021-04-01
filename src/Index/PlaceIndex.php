<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Index;

use App\Entity\Place;
use Nines\SolrBundle\Index\AbstractIndex;
use Solarium\QueryType\Select\Query\Query;

class PlaceIndex extends AbstractIndex
{

    /**
     * @param $q
     * @param array $filters
     * @param array $rangeFilters
     *
     * @return Query
     */
    public function searchQuery($q, $filters = [], $rangeFilters = []) {
        $qb = $this->createQueryBuilder();
        $qb->addFilter('type', ['Place']);
        $qb->setQueryString($q);
        $qb->setHighlightFields('content');
        $qb->setDefaultField('content');

        foreach ($filters as $key => $values) {
            $qb->addFilter($key, $values);
        }

        $qb->addFacetField('regionName');
        $qb->addFacetField('countryName');
        $qb->addSorting('sortable', 'asc');
        return $qb->getQuery();
    }

    /**
     * @param Place $place
     * @param $distance
     *
     * @return Query|null
     */
    public function nearByQuery(Place $place, $distance) {
        if( ! $place->getCoordinates()) {
            return null;
        }
        $qb = $this->createQueryBuilder();
        $qb->addGeographicFilter('coordinates', $place->getLatitude(), $place->getLongitude(), "$distance");
        $qb->addDistanceField('coordinates', $place->getLatitude(), $place->getLongitude());
        $qb->setSorting();
        $qb->addDistanceSorting('coordinates', $place->getLatitude(), $place->getLongitude(), 'asc');

        return $qb->getQuery();
    }
}
