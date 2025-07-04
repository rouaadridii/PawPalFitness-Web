<?php

namespace App\Controller\ReservationBack;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\StripeService;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class ResBackController extends AbstractController
{
    private $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    #[Route('/show', name: 'app_display')]
public function index(Request $request, ReservationRepository $reservationRepository, LoggerInterface $logger, PaginatorInterface $paginator): Response
{
    // Retrieve the search keyword from the request
    $searchKeyword = $request->query->get('searchKeyword');

    // Determine the current page number from the request (default to 1)
    $page = $request->query->getInt('page', 1);

    // Determine the search query based on the search keyword
    if ($searchKeyword) {
        $query = $reservationRepository->createQueryBuilder('r')
            ->where('r.category LIKE :searchKeyword')
            ->orWhere('r.date LIKE :searchKeyword')
            ->setParameter('searchKeyword', '%' . $searchKeyword . '%')
            ->getQuery();
    } else {
        $query = $reservationRepository->createQueryBuilder('r')->getQuery();
    }

    // Paginate the results
    $reservations = $paginator->paginate(
        $query,  // Query to paginate
        $page,   // Current page number
        5        // Number of items per page
    );

    // Log the reservations data
    $logger->info('Reservations data:', ['reservations' => $reservations]);

    // Return the rendered template with paginated reservations
    return $this->render('reservation/displayRes.html.twig', [
        'reservations' => $reservations,
    ]);
}


    #[Route('/add', name: 'app_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Redirect to the appropriate route after successful form submission
            return $this->redirectToRoute('app_display', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the form template with the form object
        return $this->render('reservation/add.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);

}


#[Route('/edit/{reservationid}', name: 'app_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
{
    // Create the form and bind it to the reservation entity
    $form = $this->createForm(ReservationType::class, $reservation, ['is_edit' => true]);
        
    // Manually populate form fields with values from the reservation entity
    $form->get('places')->setData($reservation->getPlaces());
    $form->get('category')->setData($reservation->getCategory());
    $form->get('date')->setData($reservation->getDate());
    $form->get('startTime')->setData($reservation->getStartTime());
    $form->get('endTime')->setData($reservation->getEndTime());
    $form->get('status')->setData($reservation->getStatus());
    $form->get('duration')->setData($reservation->getDuration());
    $form->get('pricing')->setData($reservation->getPricing());

    // Handle the form request
    $form->handleRequest($request);

    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Flush changes to the database
        $entityManager->flush();

        // Redirect to the display page
        return $this->redirectToRoute('app_display', [], Response::HTTP_SEE_OTHER);
    }

    // Render the edit form template with the reservation entity and form view
    return $this->render('reservation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form->createView(), // Pass the form view to the template
    ]);
}




#[Route('/delete/{id}', name: 'app_delete', methods: ['POST'])]
public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
{
    // Validate CSRF token
    if ($this->isCsrfTokenValid('delete'.$reservation->getReservationId(), $request->request->get('_token'))) {
        $entityManager->remove($reservation);
        $entityManager->flush();

        // Redirect back to the reservation display page after deletion
        return $this->redirectToRoute('app_display');
    }

    // If CSRF token is not valid, handle the error as needed
    // For example, you can redirect back to the display page with an error message
    return $this->redirectToRoute('app_display', ['error' => 'Invalid CSRF token']);
}

#[Route('/generate-pdf', name: 'generate_pdf')]
public function generatePdf(ReservationRepository $reservationRepository): Response
{
    // Fetch all reservations and organize them by date
    $reservations = $reservationRepository->findAll();
    $reservationsByDate = [];

    foreach ($reservations as $reservation) {
        $date = $reservation->getDate();
        if (!isset($reservationsByDate[$date])) {
            $reservationsByDate[$date] = [];
        }
        $reservationsByDate[$date][] = $reservation;
    }

    // Create an instance of Dompdf
    $dompdf = new \Dompdf\Dompdf();

    // Render the template to HTML
    $html = $this->renderView('reservation/pdf_template.html.twig', [
        'reservationsByDate' => $reservationsByDate,
    ]);

    // Load HTML content into Dompdf
    $dompdf->loadHtml($html);

    // Render the PDF
    $dompdf->render();

    // Output the generated PDF 
    return new Response($dompdf->stream('reservations.pdf', ['Attachment' => 0]), 200, [
        'Content-Type' => 'application/pdf',
    ]);
}

#[Route('/chart-data', name: 'chart_data')]
public function chartData(ReservationRepository $reservationRepository): JsonResponse
{
    // Get the data grouped by category
    $reservationsByCategory = $reservationRepository->getReservationCountByCategory();
    
    // Convert data into a format suitable for a chart
    $categories = [];
    $counts = [];
    
    foreach ($reservationsByCategory as $data) {
        $categories[] = $data['category'];
        $counts[] = $data['count'];
    }
    
    // Return the data as a JSON response
    return new JsonResponse([
        'categories' => $categories,
        'counts' => $counts,
    ]);
}



}