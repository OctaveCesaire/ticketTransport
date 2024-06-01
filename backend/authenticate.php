<?php
    session_start();

    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['customer_email'];
        $password = $_POST['password'];

        $stmt = $mysqlClient->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pswd'])) {
            $_SESSION['customer_id'] = $user['id'];
            header('Location: /');
            exit;
        } else {
            // Authentification échouée
            header('Location: /pages/auth.php');
            exit;
        }
    } else {
        echo "Aucune donnée reçue.";
    }
?>
