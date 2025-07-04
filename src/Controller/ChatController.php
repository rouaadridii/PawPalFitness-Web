<?php

namespace App\Controller;

use App\Service\ChatGPTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    
    #[Route('/send-message', name: 'send_message', methods: ['POST'])]
    public function sendPrompt(Request $request, ChatGPTService $chatGPTService): Response
    {
        // Get the user prompt from the request
        $prompt = $request->request->get('prompt');

        // Call the chatWithGPT method from the ChatGPTService
        $response = $chatGPTService->chatWithGPT($prompt);

        // Return a JSON response with the ChatGPT response
        return $this->json([
            'response' => $response
        ]);
    }
}