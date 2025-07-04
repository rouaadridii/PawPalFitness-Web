<?php

namespace App\Controller\Reservation;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationController extends AbstractController
{
    #[Route('/planning', name: 'planning')]
    public function planning(ReservationRepository $reservationRepository, SessionInterface $session): Response
    {
        // Fetch reservations from the database
        $reservations = $reservationRepository->findAll();

        // Organize reservations by category
        $reservationsByCategory = [];
        foreach ($reservations as $reservation) {
            $category = $reservation->getCategory();
            if (!isset($reservationsByCategory[$category])) {
                $reservationsByCategory[$category] = [];
            }
            $reservationsByCategory[$category][] = $reservation;
        }

        // Calculate the total count of items in the cart
        $cartCount = $this->calculateCartCount($session);

        // Render the template with reservations organized by category and the cart count
        return $this->render('reservation/reservation.html.twig', [
            'reservationsByCategory' => $reservationsByCategory,
            'cart_count' => $cartCount,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(int $id, SessionInterface $session, ReservationRepository $reservationRepository): Response
    {
        // Retrieve the cart from the session
        $cart = $session->get('cart', []);

        // Increment the count for the reservation ID in the cart
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            // Add reservation to the cart
            $reservation = $reservationRepository->find($id);
            if ($reservation) {
                $cart[$id] = 1;
            }
        }

        // Store the updated cart in the session
        $session->set('cart', $cart);

        // Redirect back to the reservation page
        return $this->redirectToRoute('planning');
    }

    private function calculateCartCount(SessionInterface $session): int
    {
        // Retrieve the cart from the session
        $cart = $session->get('cart', []);

        // Calculate the total count of items in the cart
        return array_sum($cart);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(Request $request, SessionInterface $session, ReservationRepository $reservationRepository): Response
    {
        // Define your Stripe public key
        $stripePublicKey = $_ENV['STRIPE_PUBLIC_KEY'];

        // Calculate cart count and retrieve cart details
        $cartCount = $this->calculateCartCount($session);
        $cartDetails = $this->getCartDetails($session, $reservationRepository);

        // Render the checkout page template
        return $this->render('reservation/checkout.html.twig', [
            'stripe_public_key' => $stripePublicKey,
            'cart_count' => $cartCount,
            'cart_items' => $cartDetails['cart_items'],
            'total_price' => $cartDetails['total_price'],
        ]);
    }

    private function getCartDetails(SessionInterface $session, ReservationRepository $reservationRepository): array
    {
        // Retrieve the cart from the session
        $cart = $session->get('cart', []);

        // Calculate the total price and fetch cart items
        $totalPrice = 0;
        $cartItems = [];
        
        foreach ($cart as $reservationId => $quantity) {
            // Fetch the reservation from the repository
            $reservation = $reservationRepository->find($reservationId);
            
            if ($reservation) {
                $itemPrice = $reservation->getPricing() * $quantity;
                $totalPrice += $itemPrice;
                
                $cartItems[] = [
                    'name' => $reservation->getCategory(),
                    'price' => $itemPrice,
                    'quantity' => $quantity,
                ];
            }
        }
        
        return ['cart_items' => $cartItems, 'total_price' => $totalPrice];
    }

    #[Route('/process-payment', name: 'process_payment', methods: ['POST'])]
    public function processPayment(Request $request, LoggerInterface $logger): Response
    {
        // Retrieve payment details from the request
        $data = json_decode($request->getContent(), true);
        $paymentMethodId = $data['payment_method_id'] ?? null;
        $amount = $data['amount'] ?? 500; // Amount in cents, e.g., $5.00 is 500 cents
        $currency = 'usd'; // Currency of the payment
    
        // Check for a valid payment method ID
        if (!$paymentMethodId) {
            $logger->warning('Invalid payment method ID provided.');
            return new JsonResponse(['success' => false, 'message' => 'Invalid payment method ID'], 400);
        }
    
        // Define the return URL for the PaymentIntent
        $returnUrl = $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL);
    
        try {
            // Create a PaymentIntent with the provided details and return URL
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => $returnUrl,
            ]);
    
            // Handle PaymentIntent status
            if ($paymentIntent->status === 'succeeded') {
                // Payment succeeded, return success response
                return new JsonResponse(['success' => true, 'paymentIntent' => $paymentIntent]);
            } else if ($paymentIntent->status === 'requires_action') {
                // Payment requires further action (e.g., authentication)
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Payment requires further action.',
                    'requires_action' => true,
                    'paymentIntent' => $paymentIntent,
                ], 400);
            } else {
                // Handle other statuses
                return new JsonResponse(['success' => false, 'message' => 'Payment failed.'], 400);
            }
        } catch (ApiErrorException $e) {
            // Log the error
            $logger->error('Payment processing error: ' . $e->getMessage());
            return new JsonResponse(['success' => false, 'message' => 'Payment failed.'], 400);
        }
    }
    
    #[Route('/payment-success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        // Render the success page template
        return $this->render('reservation/success.html.twig');
    }
}
