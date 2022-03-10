<?php

namespace App\Repository;

use App\Entity\Panniers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Panniers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panniers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panniers[]    findAll()
 * @method Panniers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanniersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panniers::class);
    }

    // /**
    //  * @return Panniers[] Returns an array of Panniers objects
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
    public function findOneBySomeField($value): ?Panniers
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
