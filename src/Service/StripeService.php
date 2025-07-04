<?php
namespace App\Service;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        // Initialize Stripe with your API key from .env
        Stripe::setApiKey($_ENV['STRIPE_PUBLIC_KEY']);
    }

    public function createPaymentIntent(int $amount, string $currency = 'usd'): PaymentIntent
    {
        // Create a payment intent with specified amount and currency
        try {
            return PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
            ]);
        } catch (ApiErrorException $e) {
            throw new \RuntimeException('Stripe error: ' . $e->getMessage());
        }
    }
}
