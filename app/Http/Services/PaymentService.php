<?php

namespace App\Http\Services;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentService
{
    public static function checkout($lineItems,$orderId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::create([
            'metadata' => [
                'order_id' => $orderId
            ],
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel'
        ]);
       
        return $session->url;
    }
}