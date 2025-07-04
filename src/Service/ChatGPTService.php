<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

class ChatGPTService
{
    private $httpClient;
    private $apiKey;
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';
    private $model = 'gpt-3.5-turbo';
    private $minRequestInterval = 1000; // in milliseconds
    private $lastRequestTime = 0;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function chatWithGPT(string $prompt): string
    {
        // Check if enough time has passed since the last request
        $currentTime = round(microtime(true) * 1000);
        $elapsedTime = $currentTime - $this->lastRequestTime;

        if ($elapsedTime < $this->minRequestInterval) {
            usleep(($this->minRequestInterval - $elapsedTime) * 1000); // Wait to meet the minimum interval
        }

        // Construct the request body
        $requestBody = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'i have an application idea that will be developed on java and web, the app idea is about a gym that you can bring your animal to train with you, you can also make a coach reservation by choosing a coach from a list of coaches, Notre application favorise la santé mentale des clients en réduisant leur stress grâce à nos spécialistes aussi. On peut créer des emplois pour les coachs et les salles qui adoptent ce concept. Pour consulter profil, clicker on bouton "Profile".Pour consulter(inscrire) les reservations cliquer sur le bouton "Planning".Pour consulter vos animaux cliquer sur "Mes animaux".Pour consulter/acheter produits cliquer sur "Mes Produits".Pour consulter les abonnements cliquer sur "Mes abonnements".Pour deconnecter cliquer sur le bouton rouge dans le coin haut a droite et pour quitter l"application cliquer sur le bouton "Quitter". You can ONLY Answer questions that are related to my gym application'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ];

        try {
            // Send the request to ChatGPT API
            $response = $this->httpClient->request(
                'POST',
                $this->apiUrl,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $requestBody,
                ]
            );

            // Update the time of the last request
            $this->lastRequestTime = round(microtime(true) * 1000);

            // Check the response status code
            if ($response->getStatusCode() === 200) {
                // Extract and return the message from the response
                $responseData = $response->toArray();
                return $responseData['choices'][0]['message']['content'] ?? 'No response content';
            } else {
                return 'Error occurred: HTTP response code ' . $response->getStatusCode();
            }
        } catch (ClientException | TransportExceptionInterface $e) {
            // Handle exceptions
            return 'Error occurred while communicating with ChatGPT API: ' . $e->getMessage();
        }
    }
}
