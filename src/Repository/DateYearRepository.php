<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\DateYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|DateYear find($id, $lockMode = null, $lockVersion = null)
 * @method null|DateYear findOneBy(array $criteria, array $orderBy = null)
 * @method DateYear[]    findAll()
 * @method DateYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateYearRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, DateYear::class);
    }

    // /**
    //  * @return DateYear[] Returns an array of DateYear objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */

    /*
    public function findOneBySomeField($value): ?DateYear
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */
}
