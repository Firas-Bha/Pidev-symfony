<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findEntitiesByString($str){
        $qb = $this->createQueryBuilder('e');
        $qb->select('e')
            ->where('e.Nom LIKE :str')
            ->setParameter('str', '%' .$str . '%');

        return $qb->getQuery()->getResult();
    }

    public function OrderByCapaciteDQL(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('
    select c from App\Entity\Evenement c order by c.Capacite ASC');

        return $query->getResult();
    }

    public function OrderByNomDQL(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('
    select c from App\Entity\Evenement c order by c.Nom DESC');

        return $query->getResult();
    }
    public function OrderByDateDQL(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('
    select c from App\Entity\Evenement c order by c.Date DESC');

        return $query->getResult();
    }
}
