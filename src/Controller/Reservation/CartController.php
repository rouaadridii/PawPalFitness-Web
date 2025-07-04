<?php

namespace App\Controller\Reservation;

use App\Entity\Cart;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/cart', name: 'cartview')]
public function viewCart(SessionInterface $session, ReservationRepository $reservationRepository): Response
{
    // Retrieve the cart from the session
    $cart = $session->get('cart', []);
    
    // Fetch reservations from the cart
    $reservationsInCart = [];
    foreach ($cart as $reservationId => $quantity) {
        $reservation = $reservationRepository->find($reservationId);
        if ($reservation) {
            // Add the reservation and its quantity to the list
            $reservationsInCart[] = [
                'reservation' => $reservation,
                'quantity' => $quantity,
            ];
        }
    }

    // Render the cart template and pass the reservations in the cart
    return $this->render('reservation/cart.html.twig', [
        'reservationsInCart' => $reservationsInCart,
    ]);
}

#[Route('/remove-from-cart/{id}', name: 'remove_from_cart', methods: ['POST'])]
public function removeFromCart(int $id, SessionInterface $session): Response
{
    // Retrieve the cart from the session
    $cart = $session->get('cart', []);
    
    // Remove the reservation from the cart
    if (isset($cart[$id])) {
        unset($cart[$id]);
    }
    
    // Update the cart in the session
    $session->set('cart', $cart);
    
    // Redirect back to the cart page or reservation page
    return $this->redirectToRoute('cart'); // Adjust the redirect route as needed
}


}
