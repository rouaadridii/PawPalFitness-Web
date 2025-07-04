<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function countAnimalsByCategoryId($categoryId)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.ida)')
            ->andWhere('a.idc = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAnimalsByCategories()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.ida) as count', 'c.nomc as categoryName')
        ->leftJoin('a.idc', 'c')
        ->groupBy('a.idc')
        ->getQuery()
        ->getResult();
}


    public function searchByName(string $searchTerm): ?Animal
    {
    return $this->createQueryBuilder('a')
        ->andWhere('a.nom = :searchTerm')
        ->setParameter('searchTerm', $searchTerm)
        ->getQuery()
        ->getOneOrNullResult();
    }


}
