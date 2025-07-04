<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class UpdatePasswordController extends AbstractController
{
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/update/password/{id}', name: 'app_update_password')]
    public function index(Request $request, int $id): Response
    {
        $user = $this->entityManager->getRepository(Personne::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        if ($request->isMethod('POST')) {
            $currentPassword = $request->request->get('current-password');
            $newPassword = $request->request->get('new-password');
            $confirmPassword = $request->request->get('confirm-password');
            if ($newPassword !== $confirmPassword) {
                $this->addFlash('error', 'Passwords do not match');
                return $this->redirectToRoute('app_update_password', ['id' => $id]);
            }
            if (!$user->checkPassword($currentPassword)) {
                $this->addFlash('error', 'Invalid current password');
                return $this->redirectToRoute('app_update_password', ['id' => $id]);
            }
            $user->setPassword($newPassword);
            $this->entityManager->flush();
            $this->sendPasswordUpdatedEmail($user);
            $this->addFlash('success', 'Password updated successfully');
            return $this->redirectToRoute('app_profile', ['id' => $id]);
        }
        return $this->render('update_password/index.html.twig', [
            'user' => $user,
        ]);
    }

    private function sendPasswordUpdatedEmail(Personne $user): void
{
    $loginLink = $this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);

    $email = (new Email())
        ->from('amenallah.laouini@esprit.tn') 
        ->to($user->getEmail())
        ->subject('Password Updated')
        ->text('Your password has been updated successfully. You can login using the following link: '.$loginLink);

    $this->mailer->send($email);
}

}
