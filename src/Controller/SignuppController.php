<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\PersonneType;
use App\Form\TravailleurType;

class SignuppController extends AbstractController
{
    #[Route('/signupp', name: 'app_signupp')]
    public function index(): Response
    {
        $personneForm = $this->createForm(PersonneType::class);
        $travailleurForm = $this->createForm(TravailleurType::class);
        dump($personneForm);
        dump($travailleurForm);

        $errorMessage = '';

        return $this->render('signupp/index.html.twig', [
            'personneForm' => $personneForm->createView(),
            'travailleurForm' => $travailleurForm->createView(),
            'errorMessage' => $errorMessage, // Pass the errorMessage variable to the template
        ]);
    }
}
