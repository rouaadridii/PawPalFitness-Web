<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShowUsersController extends AbstractController
{
    private $entityManager;
    private $paginator;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->mailer = $mailer;
    }

    #[Route('/show/users', name: 'app_show_users')]
    public function index(Request $request): Response
    {
        $query = $this->entityManager->getRepository(Personne::class)->createQueryBuilder('p')->getQuery();

        // Paginate the query
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Get the current page number, default to 1
            10 // Items per page
        );

        // Render the template with the paginated results
        return $this->render('show_users/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/delete/user/{id}', name: 'app_delete_user')]
    public function deleteUser(Personne $user): RedirectResponse
    {
        // Delete the user
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        // Redirect back to the show users page
        return $this->redirectToRoute('app_show_users');
    }

    #[Route('/ban/user/{id}', name: 'app_ban_user')]
    public function banUser(Personne $user): RedirectResponse
    {
        // Ban the user
        $user->setIsBanned(true);
        $this->entityManager->flush();

        // Send email to the banned user
        $this->sendBanEmail($user);

        // Redirect back to the show users page
        return $this->redirectToRoute('app_show_users');
    }

    #[Route('/unban/user/{id}', name: 'app_unban_user')]
    public function unbanUser(Personne $user): RedirectResponse
    {
        // Unban the user
        $user->setIsBanned(false);
        $this->entityManager->flush();

        // Redirect back to the show users page
        return $this->redirectToRoute('app_show_users');
    }

    #[Route('/export-users-csv', name: 'export_users_csv')]
    public function exportUsersCsv(): Response
    {
        // Fetch all users
        $users = $this->entityManager->getRepository(Personne::class)->findAll();

        // Prepare CSV data
        $output = fopen('php://temp', 'w+');
        fputcsv($output, ['ID', 'Nom', 'Prénom', 'Région', 'Email', 'Age', 'Role']);
        foreach ($users as $user) {
            fputcsv($output, [
                $user->getId(),
                $user->getNom(),
                $user->getPrenom(),
                $user->getRegion(),
                $user->getEmail(),
                $user->getAge(),
            ]);
        }
        rewind($output);

        // Create response with CSV data
        $csv = stream_get_contents($output);
        fclose($output);
        $response = new Response($csv);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'users.csv'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'text/csv');
        return $response;
    }

    private function sendBanEmail(Personne $user): void
    {
        $email = (new Email())
            ->from('your_email@example.com')
            ->to($user->getEmail())
            ->subject('You have been banned')
            ->text('Dear '.$user->getNom().', you have been banned from our platform.');

        $this->mailer->send($email);
    }
}
