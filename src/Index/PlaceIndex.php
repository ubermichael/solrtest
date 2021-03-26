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
use Solarium\Core\Query\Helper;

class PlaceIndex extends AbstractIndex
{
    public function nearBy(Place $place, $distance) {
        $helper = new Helper();
        $geofilter = $helper->geofilt('location_p', $place->getLatitude(), $place->getLongitude(), $distance);
        $geodist = $helper->geodist('location_p', $place->getLatitude(), $place->getLongitude());

        $qb = $this->createQueryBuilder();
        $qb->addFilter('distance', $geofilter);
        $qb->addField("distance:{$geodist}");
        $qb->setSorting([[$geodist, 'asc']]);

        return $qb->getQuery();
    }
}
