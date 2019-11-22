<?php

namespace App\Repository;

use App\Entity\ForumComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ForumComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumComments[]    findAll()
 * @method ForumComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumComments::class);
    }

    // /**
    //  * @return ForumComments[] Returns an array of ForumComments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumComments
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
