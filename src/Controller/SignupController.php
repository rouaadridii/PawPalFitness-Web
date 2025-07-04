<?php

namespace App\Controller;

use App\Form\PersonneType;
use App\Entity\Personne;
use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SignupController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);
    
        $errorMessage = ''; 
    
        if ($form->isSubmitted() && $form->isValid()) {
            $emailExists = $this->checkEmailExists($personne->getEmail());
            if ($emailExists) {
                $errorMessage = 'Email already exists';
            } else {
                try {
                    $defaultRoleId = 2;
                    $role = $this->entityManager->getRepository(Role::class)->find($defaultRoleId);
                    if (!$role) {
                        throw $this->createNotFoundException('Default role not found.');
                    }
    
                    $personne->setRole($role);
                    $this->entityManager->persist($personne);
                    $this->entityManager->flush();
    
                    return $this->redirectToRoute('app_success');
                } catch (\Exception $e) {
                    // Handle the error appropriately, e.g., log it or display a generic error message
                    $errorMessage = 'An error occurred while processing your request.';
                }
            }
        }
    
        return $this->render('signup/signup.html.twig', [
            'form' => $form->createView(),
            'errorMessage' => $errorMessage,
        ]);
    }
    


    #[Route('/login', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('login/login.html.twig');
    }

    private function checkEmailExists(string $email): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('COUNT(p.id)')
           ->from(Personne::class, 'p')
           ->where('p.email = :email')
           ->setParameter('email', $email);
        $result = $qb->getQuery()->getSingleScalarResult();

        return $result > 0;
    }
}