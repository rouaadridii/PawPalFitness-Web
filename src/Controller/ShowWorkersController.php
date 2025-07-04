<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Travailleur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowWorkersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/show/workers', name: 'app_show_workers')]
    public function index(): Response
    {
        $travailleurs = $this->entityManager->getRepository(Travailleur::class)->findAll();

        return $this->render('show_workers/index.html.twig', [
            'travailleurs' => $travailleurs,
        ]);
    }

    #[Route('/delete/worker/{id}', name: 'app_delete_worker')]
    public function deleteWorker(Travailleur $travailleur): RedirectResponse
    {
        $personne = $travailleur->getPersonne();
        $this->entityManager->remove($travailleur);
        if ($personne) {
            $this->entityManager->remove($personne);
        }
    
        $this->entityManager->flush();
    
        return $this->redirectToRoute('app_show_workers');
    }
    
}
