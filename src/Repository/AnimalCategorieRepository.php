<?php

namespace App\Repository;

use App\Entity\AnimalCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalCategorie>
 *
 * @method AnimalCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalCategorie[]    findAll()
 * @method AnimalCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalCategorie::class);
    }

    //    /**
    //     * @return AnimalCategorie[] Returns an array of AnimalCategorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AnimalCategorie
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
