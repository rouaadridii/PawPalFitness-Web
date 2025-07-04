<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Dompdf\Options;
/**
 * @Route("/admin/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

/**
     * @Route("/TrierParDateDESC", name="TrierParDateDESC")
     */
    public function TrierParDate(CommandeRepository $commandeRepository ,Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $commandeRepository->findByDate();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commande,
        ]);
    }

    /**
     * @Route("/new/{id}", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request , Int $id): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande -> setTotalcost($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('products_index');

        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{idc}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getidc(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }
    /**
 * @Route("/list/{id}", name="commande_list", methods={"GET"})
 */
public function listl(CommandeRepository $FactureRepository, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFront', 'Arial');

    $dompdf = new Dompdf($pdfOptions);
    
    // Fetch the specific Facture based on the ID
    $commande = $entityManager->getRepository(Commande::class)->find($id);

    // Check if Facture exists
    if (!$commande) {
        throw $this->createNotFoundException('Commande not found');
    }

    $html = $this->renderView('commande/list.html.twig', [
        'commandes' => [$commande], // Pass the Facture as an array
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $response = new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
    ]);

    // Set the response headers for downloading the file
    $response->headers->set('Content-Disposition', 'inline; filename="mypdf.pdf"');

    return $response;
}

}
