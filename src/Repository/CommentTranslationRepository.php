<?php

namespace App\Repository;

use App\Entity\CommentTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentTranslation[]    findAll()
 * @method CommentTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentTranslation::class);
    }

    // /**
    //  * @return CommentTranslation[] Returns an array of CommentTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentTranslation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
