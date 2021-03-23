<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Periodical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Periodical find($id, $lockMode = null, $lockVersion = null)
 * @method null|Periodical findOneBy(array $criteria, array $orderBy = null)
 * @method Periodical[]    findAll()
 * @method Periodical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Periodical::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('periodical')
            ->orderBy('periodical.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Periodical[]
     */
    public function typeaheadQuery($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('periodical');
        $qb->andWhere('periodical.column LIKE :q');
        $qb->orderBy('periodical.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
