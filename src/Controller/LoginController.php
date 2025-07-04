<?php


namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class LoginController extends AbstractController
{
    private $entityManager;
    private $notifier;

    public function __construct(EntityManagerInterface $entityManager, NotifierInterface $notifier)
    {
        $this->entityManager = $entityManager;
        $this->notifier = $notifier;
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, PersonneRepository $personneRepository): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $recaptchaResponse = $request->request->get('g-recaptcha-response');
        $remoteIp = $request->getClientIp();
        $recaptchaSecret = '6LcO9sQpAAAAAK3MejHv2zTxmeHFj6nFMEwdH5b3'; // Secret key of your reCAPTCHA

        // Validate reCAPTCHA
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
               'secret' => $recaptchaSecret,
               'response' => $recaptchaResponse,
                'remoteip' => $remoteIp,
            ],
        ]);

        $responseData = $response->toArray();

        // Check if reCAPTCHA validation succeeded
        if (!$responseData['success']) {
            $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
            return $this->redirectToRoute('login_page');
        }

        // Check if email and password are provided
        if (empty($email) || empty($password)) {
            $this->addFlash('error', 'Please enter both email and password.');
            return $this->redirectToRoute('login_page');
        }

        // Check if the user is a Personne
        $user = $personneRepository->findOneBy(['email' => $email]);

        // Check if the user is banned
        if ($user && $user->getIsBanned()) {
            $this->addFlash('error', 'You are banned. Please contact support for assistance.');
            return $this->redirectToRoute('login_page');
        }

        // Validate password
        if (!$user || !hash_equals($user->getPassword(), hash('sha256', $password))) {

            $this->addFlash('error', 'Invalid email or password. Please try again.');
     // Redirect to the login page
            return $this->redirectToRoute('login_page');
        }

        // Reset the failed login attempts count upon successful login
        $user->resetFailedLoginAttempts();
        $this->entityManager->flush();

        // Redirect to the appropriate dashboard
        if ($email === 'amenallah.laouini@esprit.tn' && $password === '1234') {
            return $this->redirectToRoute('app_test');
        } else {
            // Redirect to the appropriate dashboard
            return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
        }
    }
}
