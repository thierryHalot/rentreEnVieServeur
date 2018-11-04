<?php

namespace App\Repository;

use App\Entity\HeaderMsg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HeaderMsg|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeaderMsg|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeaderMsg[]    findAll()
 * @method HeaderMsg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeaderMsgRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HeaderMsg::class);
    }

//    /**
//     * @return HeaderMsg[] Returns an array of HeaderMsg objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HeaderMsg
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
