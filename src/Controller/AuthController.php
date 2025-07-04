<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class AuthController extends AbstractController
{
    #[Route('/connect/github/check', name: 'app_github_auth_check')]
    public function githubAuthCheck(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager): Response
    {
        $client = $clientRegistry->getClient('github');
        $accessToken = $client->getAccessToken();

        if (!$accessToken) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $githubUser = $client->fetchUserFromToken($accessToken);
        } catch (\Exception $e) {
            return $this->redirectToRoute('app_login');
        }

        // Extract user data from the GitHub response
        $email = $githubUser->getEmail();
        $username = $githubUser->getUsername();

        // Check if the user already exists in your database
        $existingUser = $entityManager->getRepository(Personne::class)->findOneBy(['email' => $email]);

        if (!$existingUser) {
            // Create a new Personne entity and set its properties
            $newUser = new Personne();
            $newUser->setNom($username); // Assuming GitHub username as last name
            $newUser->setEmail($email);

            // Persist the new user to the database
            $entityManager->persist($newUser);
            $entityManager->flush();

            // Redirect the user to a success page or perform any other action
            return $this->redirectToRoute('app_success');
        }

        // Redirect the user to a login page or perform any other action for existing users
        return $this->redirectToRoute('app_login');
    }
}
