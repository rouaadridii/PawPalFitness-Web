<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\PngResult;

#[Route('/admin/salle')]
class SalleController extends AbstractController
{
    private function convertQrCodeResultToString(PngResult $qrCodeResult): string
    {
        return 'data:image/png;base64,' . base64_encode($qrCodeResult->getString());
    }

    #[Route('/', name: 'app_salle_index', methods: ['GET'])]
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

        return $this->render('salle/index.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/new', name: 'app_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $salle->setImage($newFilename);
            }
            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_show', methods: ['GET'])]
    public function show(Salle $salle): Response
    {
        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $originalImage = $salle->getImage();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                if ($originalImage) {
                    $oldFilePath = $this->getParameter('images_directory').'/'.$originalImage;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $salle->setImage($newFilename);
            } elseif (!$file && $request->get('remove_image') === 'true') {
                if ($originalImage) {
                    $oldFilePath = $this->getParameter('images_directory').'/'.$originalImage;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                    $salle->setImage(null);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
    }
}
