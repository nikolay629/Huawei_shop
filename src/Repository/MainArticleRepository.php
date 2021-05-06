<?php

namespace App\Repository;

use App\Entity\MainArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MainArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainArticle[]    findAll()
 * @method MainArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainArticle::class);
    }

    // /**
    //  * @return MainArticle[] Returns an array of MainArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MainArticle
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
