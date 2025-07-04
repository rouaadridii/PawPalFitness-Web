<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Get the Doctrine connection
        $connection = $this->entityManager->getConnection();

        // Execute SQL queries to count records in each table
        $userCount = $connection->executeQuery('SELECT COUNT(*) FROM personne')->fetchOne();
        $workerCount = $connection->executeQuery('SELECT COUNT(*) FROM travailleur')->fetchOne();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user_count' => $userCount,
            'worker_count' => $workerCount,
        ]);
    }
}
