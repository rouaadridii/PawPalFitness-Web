<?php

namespace App\Repository;

use App\Entity\SalleDeSport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SalleDeSport>
 *
 * @method SalleDeSport|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalleDeSport|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalleDeSport[]    findAll()
 * @method SalleDeSport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalleDeSportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalleDeSport::class);
    }

    //    /**
    //     * @return SalleDeSport[] Returns an array of SalleDeSport objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SalleDeSport
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
