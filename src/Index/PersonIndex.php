<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Index;

use Nines\SolrBundle\Index\AbstractIndex;
use Solarium\QueryType\Select\Query\Query;

class PersonIndex extends AbstractIndex
{
    /**
     * @param $q
     * @param array $filters
     * @param array $rangeFilters
     *
     * @return Query
     */
    public function searchQuery($q, $filters = [], $rangeFilters = []) {
        $year = date('Y');
        $qb = $this->createQueryBuilder();
        $qb->setQueryString($q);
        $qb->setDefaultField('content');

        foreach ($filters as $key => $values) {
            $qb->addFilter($key, $values);
        }
        $qb->addFilter('type', ['Person']);

        foreach($rangeFilters as $key => $values) {
            foreach($values as $v) {
                list($start, $end) = explode(" ", $v);
                $qb->addFilterRange($key, $start, $end);
            }
        }

        $qb->setHighlightFields('content');
        $qb->addFacetRange('birthDate', 1750, $year, 50);
        $qb->addFacetRange('deathDate', 1750, $year, 50);
        $qb->addFacetField('birthPlace');
        $qb->addFacetField('deathPlace');
        $qb->addSorting('score', 'desc');
        $qb->addSorting('sortableName', 'asc');

        return $qb->getQuery();
    }
}
