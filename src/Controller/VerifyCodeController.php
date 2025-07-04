<?php

namespace App\Controller;

use App\Entity\VerificationCodes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerifyCodeController extends AbstractController
{
    #[Route('/verify/code', name: 'app_verify_code')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $error = $request->query->get('error');
        return $this->render('verify_code/index.html.twig', [
            'error' => $error
        ]);
    }
    
    #[Route('/verify/code/check', name: 'app_verify_code_check', methods: ['POST'])]
    public function check(Request $request, EntityManagerInterface $entityManager): Response
    {
        $code = $request->request->get('code');
        $verificationCode = $entityManager->getRepository(VerificationCodes::class)->findOneBy(['code' => $code]);
            if ($verificationCode) {
            $email = $verificationCode->getEmail();
            $entityManager->remove($verificationCode);
            $entityManager->flush();
                return $this->redirectToRoute('app_reset_password', ['email' => $email]);
        } else {
            $this->addFlash('error', 'Invalid verification code.');
                return $this->redirectToRoute('app_verify_code', ['error' => true]);
        }
    }
    
}
