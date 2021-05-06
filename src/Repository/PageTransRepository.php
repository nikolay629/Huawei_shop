<?php

namespace App\Repository;

use App\Entity\PageTrans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PageTrans|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTrans|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTrans[]    findAll()
 * @method PageTrans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTransRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTrans::class);
    }

    // /**
    //  * @return PageTrans[] Returns an array of PageTrans objects
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
    public function findOneBySomeField($value): ?PageTrans
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
