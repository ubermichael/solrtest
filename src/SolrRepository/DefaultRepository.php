<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\SolrRepository;

use Nines\SolrBundle\Repository\SolrRepository;

class DefaultRepository extends SolrRepository
{
    public function search($q, $filters) {
        $qb = $this->createQueryBuilder();
        $qb->setQueryString($q);
        $qb->setDefaultField('content_txt');

        foreach ($filters as $key => $values) {
            $qb->addFilter($key, $values);
        }
        $qb->setHighlightFields('content_txt');
        $qb->addFacetField('type');
        $qb->addFacetField('genres');

        return $qb->getQuery();
    }
}
