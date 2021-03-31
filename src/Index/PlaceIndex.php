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
        $qb = $this->createQueryBuilder();
        $qb->addGeographicFilter('coordinates', $place->getLatitude(), $place->getLongitude(), "$distance");
        $qb->addDistanceField('coordinates', $place->getLatitude(), $place->getLongitude());
        $qb->setSorting();
        $qb->addDistanceSorting('coordinates', $place->getLatitude(), $place->getLongitude(), 'asc');

        return $qb->getQuery();
    }
}
