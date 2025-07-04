<?php

// src/Controller/ForgotPasswordController.php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\VerificationCodes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot/password', name: 'app_forgot_password')]
    public function index(Request $request): Response
    {
        // Check if there is an error message passed as a query parameter
        $error = $request->query->get('error');
    
        // Render the forgot password form and pass the error variable to the template
        return $this->render('forgot_password/index.html.twig', [
            'error' => $error
        ]);
    }
    #[Route('/forgot/password/send', name: 'app_send_forgot_password_email', methods: ['POST'])]
    public function sendForgotPasswordEmail(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        // Retrieve email address from the form submission
        $email = $request->request->get('email');
    
        // Check if the email exists in the database
        $user = $entityManager->getRepository(Personne::class)->findOneBy(['email' => $email]);
        if (!$user) {
            // If email is not found, set a flash message and redirect back to the forgot password page
            $this->addFlash('error', 'Email address not found.');
            return $this->redirectToRoute('app_forgot_password');
        }
    
        // Generate a unique verification code
        $verificationCode = $this->generateCode();
    
        // Save the verification code in the database
        $verificationCodeEntity = new VerificationCodes();
        $verificationCodeEntity->setEmail($email);
        $verificationCodeEntity->setCode($verificationCode);
        $entityManager->persist($verificationCodeEntity);
        $entityManager->flush();
    
        // Send the verification code email
        try {
            $this->sendEmail($mailer, $email, $verificationCode);
            $this->addFlash('success', 'Verification code sent successfully.');
            
            // Redirect to the reset password page and pass the email as a query parameter
            return $this->redirectToRoute('app_verify_code', ['email' => $email]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to send verification code. Please try again later.');
        }
    
        // If sending email fails or encounters an exception, redirect back to the forgot password page
        return $this->redirectToRoute('app_forgot_password');
    }
    
    
    // Helper method to generate a verification code
    private function generateCode(): string
    {
        // Generate a random 6-digit code
        return strval(mt_rand(100000, 999999));
    }

    // Helper method to send email
    private function sendEmail(MailerInterface $mailer, string $recipientEmail, string $verificationCode): void
    {
        // Use Symfony Mailer to send email
        $email = (new Email())
            ->from('PawpalFitness@gmail.com')
            ->to($recipientEmail)
            ->subject('Password Reset Code')
            ->text('Your verification code is: ' . $verificationCode);

        $mailer->send($email);
    }
}
