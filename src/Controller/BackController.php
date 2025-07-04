<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BackController extends AbstractController
{
    #[Route('/Back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('baseback.html.twig');
    }
    
}
