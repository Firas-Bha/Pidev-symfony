<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    // /**
    //  * @return Cours[] Returns an array of Cours objects
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
    public function findOneBySomeField($value): ?Cours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Returns number of "cours" per day
     * @return void
     */
    public function countByDate(){
         $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.DateC, 1, 10) as DateC, COUNT(a) as count')
            ->groupBy('DateC')
         ;
         return $query->getQuery()->getResult();
        /*$query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count FROM App\Entity\Annonces a GROUP BY dateAnnonces
        ");
        return $query->getResult();*/
    }

    /**
     * Returns all Cours per page
     * @return void
     */
    public function getPaginatedCours($pageC, $limitC)
    {
        $query = $this->createQueryBuilder('aC');


        $query->orderBy('aC.NiveauC')
            ->setFirstResult(($pageC * $limitC) - $limitC)
            ->setMaxResults($limitC)
        ;
        return $query->getQuery()->getResult();
    }

    public function getTotalCours($filters = null){
        $query = $this->createQueryBuilder('aC')
            ->select('COUNT(aC)')
        ;

        return $query->getQuery()->getSingleScalarResult();
    }

}
