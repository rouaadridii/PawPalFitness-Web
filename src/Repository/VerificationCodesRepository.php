<?php

namespace App\Repository;

use App\Entity\VerificationCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VerificationCodes>
 *
 * @method VerificationCodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerificationCodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerificationCodes[]    findAll()
 * @method VerificationCodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerificationCodesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerificationCodes::class);
    }

    //    /**
    //     * @return VerificationCodes[] Returns an array of VerificationCodes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VerificationCodes
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
