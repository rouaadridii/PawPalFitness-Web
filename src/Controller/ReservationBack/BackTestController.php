<?php

namespace App\Controller\ReservationBack;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BackTestController extends AbstractController
{
    #[Route('/displayRes', name: 'app_display')]
    public function display(): Response
    {
        return $this->render('reservation/index.html.twig');
    }
}
