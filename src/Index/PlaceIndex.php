<?php


namespace App\Index;


use App\Entity\Place;
use Nines\SolrBundle\Index\AbstractIndex;
use Solarium\Core\Query\Helper;
use Solarium\QueryType\Select\Query\Query;

class PlaceIndex extends AbstractIndex {

    public function nearBy(Place $place, $distance) {
        $helper = new Helper();
        $geofilter = $helper->geofilt('location_p', $place->getLatitude(), $place->getLongitude(), $distance);
        $geodist = $helper->geodist('location_p', $place->getLatitude(), $place->getLongitude());

        $qb = $this->createQueryBuilder();
        $qb->addFilter('distance', $geofilter);
        $qb->addField("distance:{$geodist}");
        $qb->setSorting([[$geodist, 'asc']]);
        $query = $qb->getQuery();
        return $query;
    }

}
