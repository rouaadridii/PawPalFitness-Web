<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PagevitrineController extends AbstractController
{
    #[Route('/Welcome', name: 'app_pagevitrine')]
    public function index(): Response
    {
        return $this->render('pagevitrine/PageVitrine.html.twig');
    }   
    #[Route('/about', name: 'app_aboutus')]
    public function aboutus(): Response
    {
        return $this->render('pagevitrine/aboutus.html.twig');
    }

    #[Route('/team', name: 'app_team')]
    public function team(): Response
    {
        return $this->render('pagevitrine/ourteam.html.twig');
    }
    #[Route('/planning', name: 'planning')]
    public function planning(): Response
    {
        return $this->render('reservation/reservation.html.twig');
    }
    

}
