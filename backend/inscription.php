<?php

use Stripe\Terminal\Location;

session_start();
    // Connexion à la base de données
    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');

        if (!isset($_POST['username'], $_POST['customer_email'], $_POST['password'])) {
            exit("Veuillez remplir tous les champs");
        }

        // Préparation de la requête d'insertion avec des marqueurs de paramètres
        $query = "INSERT INTO users (fullname, email, pswd) VALUES (:username, :customer_email, :password)";
        $stmt = $mysqlClient->prepare($query);

        // Liaison des valeurs aux marqueurs de paramètres
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->bindParam(':customer_email', $_POST['customer_email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($_POST['password'],PASSWORD_BCRYPT,[223]), PDO::PARAM_STR);
        // Exécution de la requête préparée
        $stmt->execute();
        $_SESSION['customer_id'] = $mysqlClient->lastInsertId();
        
        header('Location:../pages/reservation.php');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
?>
