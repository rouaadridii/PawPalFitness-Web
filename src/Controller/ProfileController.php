<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Personne; // Import your User entity here
use Doctrine\ORM\EntityManagerInterface; // Import EntityManagerInterface
use Symfony\Component\HttpFoundation\Request;
class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile/{id}', name: 'app_profile')]
    public function index(int $id): Response
    {
        $user = $this->entityManager->find(Personne::class, $id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }
    #[Route('/profile/{id}/delete', name: 'app_delete_account', methods: ['POST'])]
    public function deleteAccount(Request $request, int $id): Response
    {
        $user = $this->entityManager->find(Personne::class, $id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($this->isCsrfTokenValid('delete_account', $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();


            return $this->redirectToRoute('app_login');
        }
        return $this->redirectToRoute('app_profile', ['id' => $id]);
    }
}
