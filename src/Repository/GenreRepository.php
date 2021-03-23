<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Genre find($id, $lockMode = null, $lockVersion = null)
 * @method null|Genre findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Genre::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('genre')
            ->orderBy('genre.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Genre[]
     */
    public function typeaheadQuery($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('genre');
        $qb->andWhere('genre.column LIKE :q');
        $qb->orderBy('genre.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $q
     *
     * @return Query
     */
    public function searchLabelQuery($q) {
        $qb = $this->createQueryBuilder('genre');
        $qb->addSelect('MATCH (genre.label) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Query
     */
    public function searchDescriptionQuery($q) {
        $qb = $this->createQueryBuilder('genre');
        $qb->addSelect('MATCH (genre.description) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Query
     */
    public function searchLabelDescriptionQuery($q) {
        $qb = $this->createQueryBuilder('genre');
        $qb->addSelect('MATCH (genre.label, genre.description) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
