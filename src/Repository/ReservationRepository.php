<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\Query\ResultSetMappingBuilder;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Search for reservations based on a query and attribute.
     *
     * @param string $query The search query.
     * @param string $attribute The attribute to search by (e.g., 'date', 'places', 'pricing', 'status').
     * @return Reservation[] Returns an array of Reservation objects.
     */
  // ReservationRepository.php

  public function searchReservations(array $criteria): array
  {
      // Create a query builder
      $qb = $this->createQueryBuilder('r');
      
      // Check and apply search criteria for date
      if (!empty($criteria['date'])) {
          $qb->andWhere('r.date = :date')
             ->setParameter('date', $criteria['date']);
      }
      
      // Check and apply search criteria for category
      if (!empty($criteria['category'])) {
          $qb->andWhere('r.category = :category')
             ->setParameter('category', $criteria['category']);
      }
      
      // Add more conditions based on your requirements
      
      // Execute the query and return the results
      return $qb->getQuery()->getResult();
  }
  
  public function getReservationCountByCategory(): array
{
    $qb = $this->createQueryBuilder('r')
        ->select('r.category, COUNT(r) as count')
        ->groupBy('r.category')
        ->getQuery();
    
    return $qb->getResult();
}

}
