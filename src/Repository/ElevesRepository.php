<?php

namespace App\Repository;

use App\Entity\Eleves;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Eleves|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eleves|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eleves[]    findAll()
 * @method Eleves[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElevesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleves::class);
    }

     /**
     * @return Eleves[] Returns an array of Eleves objects
     */

    public function groupedClass($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.college = :val')
            ->andWhere('e.role = :ROLE')
            ->setParameter('ROLE', 'ROLE_USER')
            ->setParameter('val', $value)
            ->groupBy('e.classe')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Eleves[] Returns an array of Eleves objects
     */

    public function groupedfiliere($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.college = :val')
            ->andWhere('e.role = :ROLE')
            ->setParameter('ROLE', 'ROLE_PROF')
            ->setParameter('val', $value)
            ->groupBy('e.classe')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function groupedlvl($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.college = :val')
            ->andWhere('e.role = :ROLE')
            ->setParameter('ROLE', 'ROLE_USER')
            ->setParameter('val', $value)
            ->groupBy('e.niveau')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Eleves
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
