<?php

namespace App\Repository;

use App\Entity\PageTitleTrans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PageTitleTrans|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTitleTrans|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTitleTrans[]    findAll()
 * @method PageTitleTrans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTitleTransRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTitleTrans::class);
    }

    // /**
    //  * @return PageTitleTrans[] Returns an array of PageTitleTrans objects
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
    public function findOneBySomeField($value): ?PageTitleTrans
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
