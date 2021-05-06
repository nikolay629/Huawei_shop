<?php

namespace App\Repository;

use App\Entity\PageList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PageList|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageList|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageList[]    findAll()
 * @method PageList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageList::class);
    }

    // /**
    //  * @return PageList[] Returns an array of PageList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PageList
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
