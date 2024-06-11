<?php
session_start();
require "../vendor/autoload.php";

// Set your secret key
$stripe_secret_key = "sk_test_51PIfESAzOsFfHlpKCAJ4EJ70jXtDBa0RAT3tUgDwaTT9OJkaH2RFE3FcT3z959tedWix2k6SjhsHi5GJld8zXpuG00cBIdXPpe";
\Stripe\Stripe::setApiKey($stripe_secret_key);

$mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');


unset($_SESSION['collect']);$_SESSION['collect'] = array();

foreach ($_POST as $key => $value) {
    $_SESSION['collect'][$key] = $value;
}

// Récuperer l'id du trajet
$trajet_id = $mysqlClient->prepare("SELECT id FROM tarifs WHERE gares_depart = :gares_depart AND gares_arrivee =:gares_arrivee ");
$trajet_id->bindParam(':gares_depart', $_SESSION['collect']['departure'], PDO::PARAM_STR);
$trajet_id->bindParam(':gares_arrivee', $_SESSION['collect']['arrival'], PDO::PARAM_STR);
$trajet_id->execute();
$trajet_id = $trajet_id->fetch(PDO::FETCH_ASSOC);
if($trajet_id)
    $_SESSION['trajet_id'] = $trajet_id['id'];


if (empty($_POST['tarif']) || empty($_POST['email']) || empty($_POST['fullname'])) {
    die("Error: Missing required POST data.");
}

$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$fullname = htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8');

$pass = htmlspecialchars($_POST['PassType'], ENT_QUOTES, 'UTF-8');
if($pass==='Bussiness Classe'){
    $tarif = ($_POST['tarif']+20) * 100;
}elseif($pass==='Classe Economie'){
    $tarif = $_POST['tarif'] * 100;
}else{
    $tarif = ($_POST['tarif'] + 50) * 100;
}

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'success_url' => 'http://localhost/marcoprojet/pages/frontend/success.php',
        'cancel_url' => 'http://localhost/marcoprojet/pages/frontend/cancel.php',
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $tarif,
                    'product_data' => [
                        'name' => 'Ticket de Transport de: ' . $fullname
                    ],
                ],
            ],
        ],
        'customer_email' => $email,
    ]);

    http_response_code(303);
    header("Location: " . $checkout_session->url);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'Error creating checkout session: ' . $e->getMessage();
}
?>
