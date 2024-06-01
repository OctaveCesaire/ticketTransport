<?php
session_start();
require "../vendor/autoload.php";
$stripe_secret_key = "sk_test_51PIfESAzOsFfHlpKCAJ4EJ70jXtDBa0RAT3tUgDwaTT9OJkaH2RFE3FcT3z959tedWix2k6SjhsHi5GJld8zXpuG00cBIdXPpe";

\Stripe\Stripe::setApiKey($stripe_secret_key);
$_SESSION['name'] = $_POST['fname']. ' '.$_POST['lname'];

$checkout_session = \Stripe\Checkout\Session::create([
    'mode' => 'payment',
    'success_url' => 'http://marco.test/pages/frontend/success.php',
    'cancel_url' => 'http://marco.test/pages/frontend/cancel.php', // Assurez-vous d'ajouter une URL d'annulation
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'quantity' => 1,
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $_POST['tarif']*100,
                'product_data' => [
                    'name' => 'Ticket de Transport de: ' . $_SESSION['name'] // Vous pouvez ajouter plus de détails sur le produit ici
                ],
            ],
        ],
    ],
    'customer_email' => htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'),
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
