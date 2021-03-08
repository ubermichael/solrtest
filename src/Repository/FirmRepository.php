<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Firm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Firm find($id, $lockMode = null, $lockVersion = null)
 * @method null|Firm findOneBy(array $criteria, array $orderBy = null)
 * @method Firm[]    findAll()
 * @method Firm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FirmRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Firm::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('firm')
            ->orderBy('firm.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Firm[]
     */
    public function typeaheadQuery($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('firm');
        $qb->andWhere('firm.column LIKE :q');
        $qb->orderBy('firm.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
