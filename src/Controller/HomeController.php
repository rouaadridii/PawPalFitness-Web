<?php

namespace App\Controller;

use App\Entity\Abonnement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Salle;
use App\Form\AbonnementTypeFront;
use App\Repository\AbonnementRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\PngResult;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/en', name: 'app_home')]
    public function index(SalleRepository $salleRepository): Response
    {
        $qrCodeBuilder = new Builder(new PngWriter());

        $salles = $salleRepository->findAll();

        foreach ($salles as $salle) {
            // Build QR code for salle name
            $qrCodeResult = $qrCodeBuilder
                ->data($salle->getnom_salle())
                ->build();

            $qrCodeString = $this->convertQrCodeResultToString($qrCodeResult);

            $salle->setQrCode($qrCodeString);
        }

        return $this->render('home/index.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/new/{id}', name: 'app_abonnement_front', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager, SalleRepository $salleRepository, int $id): Response
    {
        $salle = $salleRepository->find($id);
        $abonnement = new Abonnement();
        $abonnement->setSalle($salle);  // Pre-set the Salle

        $form = $this->createForm(AbonnementTypeFront::class, $abonnement, [
            'salle' => $salle ? $salle->getId() : null  // Pass the salle to the form
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($abonnement);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    private function convertQrCodeResultToString(PngResult $qrCodeResult): string
    {
        return 'data:image/png;base64,' . base64_encode($qrCodeResult->getString());
    }
}
