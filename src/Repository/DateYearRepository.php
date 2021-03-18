<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DateYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateYear[]    findAll()
 * @method DateYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateYear::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('dateYear')
            ->orderBy('dateYear.id')
            ->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Collection|DateYear[]
     */
    public function typeaheadQuery($q) {
        throw new \RuntimeException("Not implemented yet.");
        $qb = $this->createQueryBuilder('dateYear');
        $qb->andWhere('dateYear.column LIKE :q');
        $qb->orderBy('dateYear.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

            
}
