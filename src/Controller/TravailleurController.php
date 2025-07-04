<?php

namespace App\Controller;

use App\Form\TravailleurType;
use App\Entity\Travailleur;
use App\Entity\Personne;
use App\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TravailleurController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/travailleur', name: 'app_travailleur')]
    public function signupTravailleur(Request $request): Response
    {
        $travailleur = new Travailleur();
        $form = $this->createForm(TravailleurType::class, $travailleur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            
            $personneData = $formData->getPersonne();
            
            if (!$personneData) {
                $personneData = new Personne();
                $formData->setPersonne($personneData);
            }
            
            $defaultRoleId = 3;
            $role = $this->entityManager->getRepository(Role::class)->find($defaultRoleId);
        
            if (!$role) {
                throw $this->createNotFoundException('Default role not found.');
            }

        
            $personneData->setRole($role);
            
            
            $personneData->setNom($form->get('personne')->get('nom')->getData());
            $personneData->setPrenom($form->get('personne')->get('prenom')->getData());
            $personneData->setRegion($form->get('personne')->get('region')->getData());
            $personneData->setEmail($form->get('personne')->get('email')->getData());
            $personneData->setPassword($form->get('personne')->get('password')->getData());
            $personneData->setAge($form->get('personne')->get('age')->getData());
        
            $this->entityManager->persist($personneData);
        
            $this->entityManager->persist($formData);
            $this->entityManager->flush();
        
            return $this->redirectToRoute('app_success');
        }
        

        return $this->render('travailleur/travailleur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('login/login.html.twig');
    }
}