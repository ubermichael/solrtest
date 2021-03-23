<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Alias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Alias find($id, $lockMode = null, $lockVersion = null)
 * @method null|Alias findOneBy(array $criteria, array $orderBy = null)
 * @method Alias[]    findAll()
 * @method Alias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AliasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Alias::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('alias')
            ->orderBy('alias.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Alias[]|Collection
     */
    public function typeaheadQuery($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('alias');
        $qb->andWhere('alias.column LIKE :q');
        $qb->orderBy('alias.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $q
     *
     * @return Query
     */
    public function searchNameQuery($q) {
        $qb = $this->createQueryBuilder('alias');
        $qb->addSelect('MATCH (alias.name) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
