<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        // Get email from the query parameters
        $email = $request->query->get('email');

        // Handle form submission
        if ($request->isMethod('POST')) {
            // Get submitted data
            $newPassword = $request->request->get('new_password');
            $confirmPassword = $request->request->get('confirm_password');

            // Fetch the user by email
            $user = $this->entityManager->getRepository(Personne::class)->findOneBy(['email' => $email]);

            if (!$user) {
                // Handle case when user does not exist
                $this->addFlash('error', 'User not found');
                return $this->redirectToRoute('app_reset_password', ['email' => $email]);
            }

            // Validate new password
            if ($newPassword !== $confirmPassword) {
                // Passwords don't match, handle error (e.g., redirect with flash message)
                $this->addFlash('error', 'Passwords do not match');
                return $this->redirectToRoute('app_reset_password', ['email' => $email]);
            }

            $user->setPassword($newPassword);

            $this->entityManager->flush();
            $this->addFlash('success', 'Password reset successfully');
            return $this->redirectToRoute('app_login');
        }

        // Render the reset password form
        return $this->render('reset_password/index.html.twig', [
            'email' => $email,
        ]);
    }
}
