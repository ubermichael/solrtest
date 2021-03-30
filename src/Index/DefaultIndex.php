<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Index;

use Nines\SolrBundle\Index\AbstractIndex;

class DefaultIndex extends AbstractIndex
{
    public function search($q, $filters) {
        $qb = $this->createQueryBuilder();
        dump($qb);
        $qb->setQueryString($q);
        $qb->setDefaultField('content');

        foreach ($filters as $key => $values) {
            $qb->addFilter($key, $values);
        }
        $qb->setHighlightFields('content');
        $qb->addFacetField('type');
        $qb->addFacetField('genres');

        return $qb->getQuery();
    }
}
