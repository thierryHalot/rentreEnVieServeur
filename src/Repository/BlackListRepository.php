<?php

namespace App\Repository;

use App\Entity\BlackList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlackList|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlackList|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlackList[]    findAll()
 * @method BlackList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlackListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlackList::class);
    }

//    /**
//     * @return BlackList[] Returns an array of BlackList objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlackList
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
